#!/usr/bin/python
"""

Credits: Pradeep Gurunathan, Lyudmila V. Slipchenko, Purdue University.
"""
try:
	import sys, re, os, random
except ImportError:
	print "module load error!!!"

def xyz_to_gms_inp(XYZFile, charge, EFP_terms, basis_args, basis_set):
	g = open(XYZFile, 'r')
	lines = g.readlines()
	#randvar = str(random.randint(0,1000000))
	polarization_g = 'f';
	dispersion_g = 'f';
	exrep_g ='f';
	transfer_g ='f';

	if EFP_terms == "EP":
		polarization_g = 't';
		dispersion_g = 'f';
		transfer_g ='f';
		exrep_g ='f';
	elif EFP_terms == "EPD":
		polarization_g = 't';
		dispersion_g = 't';
		transfer_g ='f';
		exrep_g ='f';
	elif EFP_terms == "EPDCE":
		polarization_g = 't';
		dispersion_g = 't';
		transfer_g ='t';
		exrep_g ='t';
		
	#redirect from the PHP files to website local filesystems
	gamess_input =  '../database/gamess_inp_files/' + os.path.splitext(os.path.basename(XYZFile))[0] + basis_set + '.inp'
	f = open(gamess_input, 'w')
	cc = []
	for line in lines[2:]:
		#replace lines for correct input into GAMESS
		line = re.sub('\t+',' ',line)
		line = re.sub(' +',' ',line)
		line = re.sub('^ ','',line)
		line = re.sub('C ','C 6.0 ',line)
		line = re.sub('H ','H 1.0 ',line)
		line = re.sub('N ','N 7.0 ',line)
		line = re.sub('O ','O 8.0 ',line)
		line = re.sub('F ','F 9.0 ',line)
		line = re.sub('B ','B 5.0 ',line)
		line = re.sub('P ','P 15.0 ',line)
		line = re.sub('S ','S 16.0 ',line)
		line = re.sub('K ','K 19.0 ',line)
		line = re.sub('I ','I 53.0 ',line)
		line = re.sub('Li ','Li 3.0 ',line)
		line = re.sub('Be ','Be 4.0 ',line)
		line = re.sub('Na ','Na 11.0 ',line)
		line = re.sub('Mg ','Mg 12.0 ',line)
		line = re.sub('Al ','Al 13.0 ',line)
		line = re.sub('Si ','Si 14.0 ',line)
		line = re.sub('Cl ','Cl 17.0 ',line)
		line = re.sub('Ca ','Ca 20.0 ',line)
		line = re.sub('Br ','Br 35.0 ',line)
		line = line.split(' ')
		cc.append(line)
		
	f.write(' $contrl units=angs local=ruednbrg runtyp=makefp \n')
	f.write('       mult=1 icharg=' + charge + '  coord=cart icut=11  maxit=180 $end\n')
	f.write(' $system timlim=99999 mwords=2500 $end\n')
	f.write(' $scf soscf=.f. dirscf=.t. diis=.t. CONV=1.0d-06 $end\n')
	f.write(' $basis ' + basis_args + ' $end\n')   							#default     gbasis=n31 ngauss=6 ndfunc=1 
	f.write(' $DAMP IFTTYP(1)=2,0 IFTFIX(1)=1,1 thrsh=500.0 $end\n')
	f.write(' $MAKEFP  POL=.' + polarization_g + '. DISP=.' + dispersion_g + '. CHTR=.' + transfer_g + '.  EXREP=.' + exrep_g + '. $end\n')
	f.write(' $data\n')
	f.write(' comment_field\n')
	f.write(' C1\n')
	for i in xrange(len(cc)):
		for j in xrange(5):
			f.write(' ')
			f.write(cc[i][j])
# 		f.write('\n')
	f.write(' $end\n')
	g.close()
	f.close()
	return os.path.basename(gamess_input)

def main(file_name, charge, EFP_terms, basis_args, basis_set):
	gamess_input = xyz_to_gms_inp(file_name, charge, EFP_terms, basis_args, basis_set)	
	
	print gamess_input
	return
	
if __name__ == '__main__':
	main(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5])
