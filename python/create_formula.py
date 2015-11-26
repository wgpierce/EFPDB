#!/usr/bin/python
"""
USAGE: python script.py <fullpp.log> <fragmentedpp.log>

remember: first argval is reference and second is fragmented

Credits: Pradeep Gurunathan, Lyudmila V. Slipchenko, Purdue University.
"""
try:
    import sys
    import re
    import os
    import random
except ImportError:
    print "module load error!!!"

def stringprint(XYZFile):
	try:
		inputXYZ=open(XYZFile,'r')
	except IOError:
		sys.stderr.write('Failed to open the files')
		return 1
	a = []
	b = 0
	c = ''
	dict1 = {}
	for line in inputXYZ:
		kk = re.sub(' +',' ',line)
		jj = kk.split(' ')
		a.append(jj)
	if a[2][0] == '':
		b += 1
	for i in xrange(2,len(a)):
#		print a[i][b]
		if a[i][b] in dict1:
			dict1[a[i][b]] += 1
		else:
			dict1[a[i][b]] = 1
	for key,value in sorted(dict1.items()):
		c += key
		value = str(value)
		c += value
#	print c
#	print a
	inputXYZ.close()
	return c
   
def main(file_name):
	b = stringprint(file_name)
	print b
	return
	
if __name__ == '__main__':
	main(sys.argv[1])