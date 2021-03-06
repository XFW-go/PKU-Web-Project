import cgi
import os
import sys

from mysql import rm_r


def rm(form, params, cursor):
    """
    params:
        filename: name of file
        path: directory of file, start with /
    return:
        NONE
    """
    fn = params['filename']
    fpath = params['path']

    rm_r(cursor, fpath, fn)

    return {'errno': 0}
