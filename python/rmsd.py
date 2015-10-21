######   #!/usr/bin/env python
import numpy as np
import sys
import re
def fit(P, Q):
    step_size = P.max(0)
    threshold = step_size*1e-9
    rmsd_best = kabsch_rmsd(P, Q)
    while True:
        for i in range(3):
            temp = np.zeros(3)
            temp[i] = step_size[i]
            rmsd_new = kabsch_rmsd(P+temp, Q)
            if rmsd_new < rmsd_best:
                rmsd_best = rmsd_new
                P[:, i] += step_size[i]
            else:
                rmsd_new = kabsch_rmsd(P-temp, Q)
                if rmsd_new < rmsd_best:
                    rmsd_best = rmsd_new
                    P[:, i] -= step_size[i]
                else:
                    step_size[i] /= 2
        if (step_size <= threshold).all():
            break
    return rmsd_best
def kabsch_rmsd(P, Q):
    P = rotate(P, Q)
    return rmsd(P, Q)
def rotate(P, Q):
    U = kabsch(P, Q)
    P = np.dot(P, U)
    return P
def kabsch(P, Q):
    C = np.dot(np.transpose(P), Q)
    V, S, W = np.linalg.svd(C)
    d = (np.linalg.det(V) * np.linalg.det(W)) < 0.0
    if d:
        S[-1] = -S[-1]
        V[:, -1] = -V[:, -1]
    U = np.dot(V, W)
    return U
def centroid(X):
    C = sum(X)/len(X)
    return C
def rmsd(V, W):
    D = len(V[0])
    N = len(V)
    rmsd = 0.0
    for v, w in zip(V, W):
        rmsd += sum([(v[i]-w[i])**2.0 for i in range(D)])
    return np.sqrt(rmsd/N)
def write_coordinates(atoms, V):
    N, D = V.shape
    print str(N)
    print
    for i in xrange(N):
        line = "{0:2s} {1:10.5f} {2:10.5f} {3:10.5f}".format(atoms[i], V[i, 0], V[i, 1], V[i, 2])
        print line
def get_coordinates(filename):
    f = open(filename, 'r')
    V = []
    atoms = []
    n_atoms = 0
    lines_read = 0
    try:
        n_atoms = int(f.next())
    except ValueError:
        exit("Could not obtain the number of atoms in the .xyz file.")
    f.next()
    for line in f:
        if lines_read == n_atoms:
            break
        atom = re.findall(r'[a-zA-Z]+', line)[0]
        numbers = re.findall(r'[-]?\d+\.\d*', line)
        numbers = [float(number) for number in numbers]
        if len(numbers) == 3:
            V.append(np.array(numbers))
            atoms.append(atom)
        else:
            exit("Reading the .xyz file failed in line {0}. Please check the format.".format(lines_read + 2))
        lines_read += 1
    f.close()
    V = np.array(V)
    return atoms, V
if __name__ == "__main__":
    args = sys.argv[1:]
    output = False
    i = 0
    if args[0] == '--output':
        output = True
        i += 1
    mol1 = args[i]
    mol2 = args[i+1]
    atomsP, P = get_coordinates(mol1)
    atomsQ, Q = get_coordinates(mol2)
    normal_rmsd = rmsd(P, Q)
    Pc = centroid(P)
    Qc = centroid(Q)
    P -= Pc
    Q -= Qc
    if output:
        V = rotate(P, Q)
        V += Qc
        write_coordinates(atomsP, V)
    else:
#        print "Normal RMSD:", normal_rmsd
        #print "Kabsch RMSD:", kabsch_rmsd(P, Q)
        print kabsch_rmsd(P, Q)
        #print "Fitted RMSD:", fit(P, Q)
