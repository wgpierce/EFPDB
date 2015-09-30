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

def main(file_name):
	gamess_input = xyz_to_gmsinp(file_name)	
	
	#This is where they stopped
	print gamess_input
	return
	
if __name__ == '__main__':
	main(sys.argv[1])
		