import cgi
import os
import sys
import string

from mysql import mysql

def newgrp(form, params, cursor):
    """
    params:
        group: group name
    return:
        errno
    """
    user = params['user']
    group = params['group']

    if string.lower(group) == 'root':
        msg = {'errno': 1, 'errmsg': 'Register of ROOT not allowed'}
        return msg

    sql = 'SELECT * FROM belongs WHERE group_name="%s"' % (group)
    result = mysql(sql, cursor)
    if len(result) > 0:
        msg = {'errno': 1, 'errmsg': 'Duplicate name'}
        return msg
    
    sql = 'SELECT * FROM users WHERE user_name="%s"' % (group)
    result = mysql(sql, cursor)
    if len(result) > 0:
        msg = {'errno': 1, 'errmsg': 'Duplicate name'}
        return msg

    sql = [
        'INSERT INTO belongs (group_name, user_name, is_own) ' \
        'VALUES ("%s", "%s", %d)' % (group, user, 1),

        'INSERT INTO file_list (path, filename, is_dir) ' \
        'VALUES ("%s", "%s", %d)' % ('/', group, 1)
    ]
    mysql(sql, cursor)

    msg = {'errno': 0}
    return msg
