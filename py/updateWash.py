import Slot, RFID, db
import RPi.GPIO as GPIO

rf = RFID.RFID()

clothId = rf.read()

slot = Slot.Slot()
slotId = slot.poll(pollFor = 1)
GPIO.cleanup()

if slotId == -1:
	print(-2, end='')
else:
	vals = (slotId, clothId)
	db.cursor.execute('UPDATE clothes SET slot = %s, washing = 0 WHERE id = %s', vals)
	db.obj.commit()
	print(1, end='')