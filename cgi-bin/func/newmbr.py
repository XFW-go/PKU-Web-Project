import cgi
import os
import sys

from mysql import mysql


def newmbr(form, params, cursor):
    """
    params:
        group: group name
        user2: user to add
    return:
        errno
    """
    group = params['group']
    user2 = params['user2']

    sql = 'SELECT * FROM users WHERE user_name="%s"' % (user2)
    result = mysql(sql, cursor)
    if len(result) == 0:
        return {'errno': 6, 'errmsg': 'User not exist'}

    sql = 'SELECT * FROM belongs WHERE group_name="%s" AND user_name="%s"' % (
        group, user2)
    result = mysql(sql, cursor)
    if len(result) > 0:
        return {'errno': 0}

    sql = 'INSERT INTO belongs (group_name, user_name) ' \
        'VALUES ("%s", "%s")' % (group, user2)
    mysql(sql, cursor)

    return {'errno': 0}
