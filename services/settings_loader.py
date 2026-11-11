from __future__ import absolute_import

import os

try:
    import configparser
except ImportError:  # Python 2
    import ConfigParser as configparser


SERVICE_ROOT = os.path.dirname(os.path.abspath(__file__))


def find_settings_file():
    path = os.path.join(os.path.dirname(SERVICE_ROOT), "settings.conf")
    if os.path.isfile(path):
        return path
    raise IOError("No settings.conf found at {0}".format(path))


def load_settings():
    settings_file = find_settings_file()
    config = configparser.ConfigParser()
    with open(settings_file) as handle:
        if hasattr(config, "read_file"):
            config.read_file(handle)
        else:  # Python 2
            config.readfp(handle)
    return config


def load_database_settings():
    config = load_settings()
    return {
        "host": config.get("database", "host"),
        "user": config.get("database", "username"),
        "password": config.get("database", "password"),
        "database": config.get("database", "database"),
    }
