import cgi
import os
import sys

from mysql import mysql

def delmbr(form, params):
    """
    params:
        group: group name
        user2: user to del
    return:
        NONE
    """
    group = params['group']
    user2 = params['user2']

    sql = 'DELETE FROM belongs WHERE group_name="%s" AND user_name="%s"' % (group, user2)
    mysql(sql)

    stat = '200 OK'
    msg = {'errno': 0}
    return (stat, msg)