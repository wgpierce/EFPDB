<?php
//This function sets basis options when creating input to GAMESS

function set_basis(&$basis_set, &$basis_args, &$basis_set_name) {
				$basis_set = '';
				$basis_args = '';
				
				if ($_POST['basis_set_type'] == 'Dunning') {
				
					if ($_POST['Aug'] == 'ACC') {
						$basis_set = 'aug-';
					}
				
					$basis_set = $basis_set . "cc-pV" . $_POST['D_Zetas'] . "Z";
					$basis_args = 'GBASIS=' . $_POST['Aug'] . $_POST['D_Zetas'];
					$basis_set_name = $basis_set;
				
				} else if ($_POST['basis_set_type'] == 'Pople') {
					//Gauss and Zetas
					$basis_args = " NGAUSS=6 " . "GBASIS=" . $_POST['P_Zetas'] . ' ';
					$basis_set = "6-" . $_POST['P_Zetas'];

					//Diffuse
					if ($_POST['diffuse'] == "Yes(++)") {
						$basis_set = $basis_set . '++';
						$basis_args = $basis_args . 'DIFFSP=.t. DIFFS=.t. ';
					} else if ($_POST['diffuse'] == "Yes(+)") {
						$basis_set = $basis_set . '+';
						$basis_args = $basis_args . 'DIFSP=.t. ';
					}
				
					$basis_set = $basis_set . 'G';
					$basis_set_name = $basis_set;

					//Pol. functions
					if ($_POST['d'] > 0 || $_POST['p'] > 0 || $_POST['f'] > 0) {
						$basis_set = $basis_set . '(';
						if ($_POST['d'] > 0) {
							$basis_set = $basis_set . $_POST['d'] . 'd';
							$basis_set_name = $basis_set_name . $_POST['d'] . 'd';
							$basis_args = $basis_args . 'NDFUNC=' . $_POST['d'] . ' ';
							if ($_POST['p'] > 0 || $_POST['f'] > 0) {
								$basis_set = $basis_set . ',';
							}
						}
						if ($_POST['p'] > 0) {
							$basis_set = $basis_set . $_POST['p'] . 'p';
							$basis_set_name = $basis_set_name . $_POST['p'] . 'p';
							$basis_args = $basis_args . 'NPFUNC=' . $_POST['p'] . ' ';
							if ($_POST['f'] > 0) {
								$basis_set = $basis_set . ',';
							}
						}
						if ($_POST['f'] > 0) {
							$basis_set = $basis_set . $_POST['f'] . 'f';
							$basis_set_name = $basis_set_name . $_POST['f'] . 'f';
							$basis_args = $basis_args . 'NFFUNC=' . $_POST['f'] . ' ';
						}
						$basis_set = $basis_set . ').';
					}
				}

}
