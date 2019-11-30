import conf
import RPi.GPIO as GPIO
from mfrc522 import SimpleMFRC522

class RFID:
	def __init__(self):
		self._reader = SimpleMFRC522()
		GPIO.setwarnings(False)

	def write(self, clothId):
		try:
			self._reader.write(str(clothId))

		except:
			GPIO.cleanup()
			return True

	def read(self):
		try:
			i, clothId = self._reader.read()
			GPIO.cleanup()
			return int(clothId)

		except:
			GPIO.cleanup()
			return -1
