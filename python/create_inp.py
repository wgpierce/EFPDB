#!/usr/bin/python
"""

Credits: Pradeep Gurunathan, Lyudmila V. Slipchenko, Purdue University.
"""
try:
	import sys, re
	from chemsimilarity import *
except ImportError:
    print "module load error!!!"
    
def main(file_name, charge, EFP_terms, basis_args, basis_set):
	gamess_input = xyz_to_gms_inp(file_name, charge, EFP_terms, basis_args, basis_set)	
	
	print gamess_input
	return
	
if __name__ == '__main__':
	main(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5])
