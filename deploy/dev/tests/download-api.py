"""Run against the local fixture by default; pass a base URL to check production."""
import csv
import io
import json
import sys
import urllib.error
import urllib.request
import zipfile

base = sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:8082'


def request(start, end):
    body = json.dumps(dict(start=start, end=end, agree='yes',
                           email='production-check@example.com')).encode()
    req = urllib.request.Request(base + '/api/download/', data=body,
                                 headers={'Content-Type': 'application/json'})
    try:
        with urllib.request.urlopen(req, timeout=60) as res:
            return res.status, res.headers, res.read()
    except urllib.error.HTTPError as error:
        return error.code, error.headers, error.read()


status, _, body = request(1794261600, 1794175199)
assert status == 400 and b'Start date must be on or before end date' in body
status, _, body = request('not-a-timestamp', 1794347999)
assert status == 400 and b'Invalid timestamp' in body
# The fixture and production have a known gap on October 1.
status, headers, body = request(1790805600, 1790891999)
assert status == 204 and body == b'' and headers.get('Content-Disposition') is None
status, _, body = request(1794261600, 1794347999)
assert status == 200
with zipfile.ZipFile(io.BytesIO(body)) as archive:
    assert archive.testzip() is None
    rows = list(csv.reader(io.TextIOWrapper(archive.open(archive.namelist()[0]))))
assert len(rows) == 1441
assert rows[1][2] == '2026-11-10 00:00:00'
assert rows[-1][2] == '2026-11-10 23:59:00'
print('PASS:', base, 'rejects invalid ranges, reports no data, exports 1,440 readings.')
