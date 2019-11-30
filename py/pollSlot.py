import Slot, RFID
import RPi.GPIO as GPIO

'''
	Implements pollSlot to first check RFID reader for any inputs and the read/write to a card as needed
	Used by higher level functions to get slot of added clothes
'''

def pollSlot(clothId = None):


	slot = Slot.Slot()
	rf = RFID.RFID()

	'''
		@params:
			$clothId - If supplied, ID is written to the tag and then the slot is polled.
			If not supplied, ID is read from the RFIF tag and then the slot is polled.

		@returns:
			int if an error occurs:
				-1 -> No RFID found in timeout
				-2 -> No slot polled in timeout

			dict on success:
				'slot': slot of cloth
				'id': if of cloth (If clothId is provided, returns the same)
	'''	


	rf.write(clothId)
	
	slotId = slot.poll(pollFor = 1)
	GPIO.cleanup()

	if slotId == -1:
		return -2
	else:
		return {'slot': slotId, 'id': clothId}