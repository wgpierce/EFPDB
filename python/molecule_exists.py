#!/usr/bin/python
"""
USAGE: python script.py <fullpp.log> <fragmentedpp.log>

remember: first argval is reference and second is fragmented

Credits: Pradeep Gurunathan, Lyudmila V. Slipchenko, Purdue University.
"""
try:
	import sys, re
	from chemsimilarity import *
except ImportError:
    print "module load error!!!"
    
randvar = 0

def main(file_name, dbname):
	b = stringprint(file_name)
	mol_exists = file_exists(b, dbname)

	if mol_exists:
		#file does exist
		print "/database/efp_files", #... 
		print True	#Note this is the LAST value returned and is used in upload_action.php
		return
	else:
	#File does not exist
		print False
		return
	
if __name__ == '__main__':
	main(sys.argv[1], 'database.txt')