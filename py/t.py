import Slot

sl = Slot.Slot()

print('Polling...')

i = 0

while i < 10:
	s = sl.poll(pollFor = 1)
	if s == -1:
		print('Timed out!')
	else:
		print('Slot Number: '+str(s))
	i += 1
