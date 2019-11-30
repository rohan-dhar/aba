'''
	TODO:
		Establishes the MySQL Database coonection
		Used by other scripts to access DB
		Dependancies: mysql lib for Python

'''
import mysql.connector

obj = mysql.connector.connect(host='', port=, user='', passwd='', database='');
cursor = obj.cursor()