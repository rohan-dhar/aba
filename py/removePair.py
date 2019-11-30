import Slot, db, sys
import RPi.GPIO as GPIO

try:
	i1 = int(sys.argv[1])
	s1 = int(sys.argv[2])
	i2 = int(sys.argv[3])
	s2 = int(sys.argv[4])
except:
	print(-1, end='')
	exit()



slot = Slot.Slot()

if slot.pollPairRemoved(s1, s2):
	vals = (i1, i2)
	db.cursor.execute('UPDATE clothes SET washing = 1, slot = null WHERE id = %s OR id = %s', vals)
	db.obj.commit()
	print('1', end='')
else:
	print('-2', end='')

GPIO.cleanup()