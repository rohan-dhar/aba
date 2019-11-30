import time, conf
import RPi.GPIO as GPIO


class Slot:
	def __init__(self):
		GPIO.setwarnings(False)
		GPIO.setmode(GPIO.BCM)

		for slot in conf.SLOT_PINS:
			GPIO.setup(slot['BTN'], GPIO.IN, pull_up_down = GPIO.PUD_UP)
			GPIO.setup(slot['LED'], GPIO.OUT)

	def getState(self):
		state = []
		for i in range(len(conf.SLOT_PINS)):
			slot = conf.SLOT_PINS[i]
			state.append(GPIO.input(slot['BTN']))
			# Inputs are Pulled Up
			if state[i] == GPIO.HIGH:
				state[i] = 0
			else:
				state[i] = 1

		return state

	def poll(self, pollFor, noLED = False):
		opposite = 0		
		if pollFor == 0:
			opposite = 1

		startTime = conf.getMillis()
		startState = self.getState()

		if noLED == False:
			for i in range(len(startState)):
				if startState[i] == opposite:
					GPIO.output(conf.SLOT_PINS[i]['LED'], GPIO.HIGH)
				else:
					GPIO.output(conf.SLOT_PINS[i]['LED'], GPIO.LOW)
				
		polledId = -1
		
		while conf.getMillis() - startTime <= conf.SLOT_POLL_TIMEOUT and polledId == -1:
			state = self.getState()		
			for i in range(len(conf.SLOT_PINS)):
				if startState[i] == opposite and state[i] == pollFor:
					polledId = i
					break

		if noLED == False:
			for i in range(len(startState)):
				GPIO.output(conf.SLOT_PINS[i]['LED'], GPIO.LOW)

		return polledId

	def pollPairRemoved(self, s1, s2):
		startTime = conf.getMillis()

		GPIO.output(conf.SLOT_PINS[s1]['LED'], GPIO.HIGH)
		GPIO.output(conf.SLOT_PINS[s2]['LED'], GPIO.HIGH)

		while conf.getMillis() - startTime < conf.SLOT_POLL_TIMEOUT:
			state = self.getState()
			if state[s1] == 0 and state[s2] == 0:
				GPIO.output(conf.SLOT_PINS[s1]['LED'], GPIO.LOW)
				GPIO.output(conf.SLOT_PINS[s2]['LED'], GPIO.LOW)
				return True
			elif state[s1] == 0:
				GPIO.output(conf.SLOT_PINS[s1]['LED'], GPIO.LOW)
			elif state[s2] == 0:
				GPIO.output(conf.SLOT_PINS[s2]['LED'], GPIO.LOW)


		GPIO.output(conf.SLOT_PINS[s1]['LED'], GPIO.LOW)
		GPIO.output(conf.SLOT_PINS[s2]['LED'], GPIO.LOW)
		return False
