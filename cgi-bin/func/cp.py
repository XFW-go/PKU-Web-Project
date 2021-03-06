import cgi
import os
import sys

from mysql import mysql, cp_r


def cp(form, params, cursor):
    """
    params:
        filename: name of file
        path: directory of file, start with /
        filename2: name of file
        path2: directory of file, start with /
        mv: 0 for cp, 1 for mv
    return:
        errno
    """
    fn = params['filename']
    fpath = params['path']
    fn2 = params['filename2']
    fpath2 = params['path2']
    mv = int(params['mv'])

    if fpath2 == '/':
        return {'errno': 3, 'errmsg': 'Access not authorized'}
    tmpath = fpath + '/' + fn
    if fpath2[:len(tmpath)] == tmpath:
        return {'errno': 9, 'errmsg': 'Paste into source'}

    sql = 'SELECT * FROM file_list WHERE path="%s" AND filename="%s"' % (
        fpath2, fn2)
    result = mysql(sql, cursor)
    if len(result) > 0:
        return {'errno': 7, 'errmsg': 'File of same name already existed at destination'}
    else:
        if mv == 1:
            sql = 'UPDATE file_list SET path="%s", filename="%s" WHERE path="%s" AND filename="%s"' % (
                fpath2, fn2, fpath, fn)
            mysql(sql, cursor)
        else:
            cp_r(cursor, fpath, fn, fpath2, fn2)
        return {'errno': 0}
