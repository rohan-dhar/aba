import Slot, db
import RPi.GPIO as GPIO

slot = Slot.Slot()
slotId = slot.poll(pollFor = 0)
GPIO.cleanup()

if slotId == -1:
	print(-2, end='')
else:
	vals = (slotId,)
	db.cursor.execute('UPDATE clothes SET slot = null, washing = 1 WHERE slot = %s', vals)
	db.obj.commit()
	print(1, end='')