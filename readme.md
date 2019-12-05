<H1>Aba</H1>
Aba is a smart wardrobe project, powered by the <b>Raspberry PI</b> platform and built on the <b>LAMP stack and Python.</b>
<br>
Aba is the major project developed for the Prototyping Interactive Systems course at IIIT Delhi

<br><br>
<h3>Installation Instructions</h3>
<ol>
	<li>Setup a LAMP (Linux Apache MySQL PHP) stack on a Raspberry PI 3B, 3B+ or 4</li>
	<li>Enable GPIO and SPI interface in Raspberry PI settings</li>
	<li>Setup the circuit as per <code>/py/conf.py</code> using LEDs (and Resistors), Push Buttons and MFRC522 RFID reader</li>
	<li>Clone the github repo inside Apache's public directory (<code>/var/www/</code> by default)</li>
	<li>Add the user Apache's user <code>www</code> as a sudoer by editing the <code>/etc/sudoers</code> file</li>
	<li>Create a new database add create the table <code>clother</code> by importing <code>/table.sql</code> file</li>
	<li>Update SQL credentials in <code>/core/conf.php</code> and <code>/py/conf.py</code> </li>
	<li>Visit <code>http://localhost/aba</code> to test!</li>
</ol>