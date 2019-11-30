'''
	TODO:
		Poll the slot for a newly added cloth Print and exit or gracefully exit if not possible


	PRINTS:
		Ints 0 to Num_Slots-1 : Representing slot id if slot is detected
		Int -1 : Invalid CLI argument 
		Int -2 : Unable to find RFID tag (DELETES THE CLOTH)
		Int -3 : RFID tag written to BUT no slot detected

'''

import sys, db
from pollSlot import pollSlot

try:
	clothId = int(sys.argv[1])
except:
	print(-1, end='')
	exit()


slotInfo = pollSlot(clothId)

if slotInfo == -1:
	vals = (clothId,)
	db.cursor.execute('DELETE FROM clothes WHERE id = %s LIMIT 1', vals)
	db.obj.commit()	
	print(-2, end='')
	exit()

elif slotInfo == -2:
	vals = (clothId,)
	db.cursor.execute('DELETE FROM clothes WHERE id = %s LIMIT 1', vals)
	db.obj.commit()	
	print(-3, end='')
	exit()


slot = slotInfo['slot']

vals = (slot, clothId)
db.cursor.execute('UPDATE clothes SET slot = %s WHERE id = %s', vals)
db.obj.commit()

print(slot, end='')
exit()