'''
	Defines global settings, pin numbers and core functions
'''

import time

# Maximum number of slot available for polling
MAX_SLOTS = 6

# Timeout for polling slot tag in milliseconds
SLOT_POLL_TIMEOUT = 60000 # 60s

# Slot polling delay in milliseconds
SLOT_POLL_DELAY = 100

# Pins used for slot buttons and LEDs
SLOT_PINS = [
	{
		'LED': 13,
		'BTN': 18
	},
	{
		'LED': 17,
		'BTN': 23
	},
	{
		'LED': 27,
		'BTN': 24
	},
	{
		'LED': 22,
		'BTN': 25
	},
	{
		'LED': 5,
		'BTN': 12
	},
	{
		'LED': 6,
		'BTN': 16
	}
]

# Pins numbers used by MFRC522 RFID reader (BCM)
RFID_PINS = {
	'SCK': 11,
	'SDA': 8,
	'MOSI': 10,
	'MISO': 9,
}

def getMillis():
	return int(round(time.time() * 1000))
