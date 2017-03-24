<?php		
//require_once('connect.php');//Koneksi ke database	

function cekKamus($kata){
	// cari di database	
	$konek = mysqli_connect("localhost","root","","retrival");
	$sql = "SELECT * from tb_katadasar where katadasar ='$kata' LIMIT 1";
	mysqli_query($konek, $sql);
	//print_r($sql);die();
//	echo $sql.'<br/>';
	
}

// Hapus Inflection Suffixes (â€œ-lahâ€?, â€œ-kahâ€?, â€œ-kuâ€?, â€œ-muâ€?, atau â€œ-nyaâ€?)
function Del_Inflection_Suffixes($kata){ 
	$kataAsal = $kata;
	if(preg_match('/([km]u|nya|[kl]ah|pun)$/',$kata)){ // Cek Inflection Suffixes
		$kata = preg_match('/([km]u|nya|[kl]ah|pun)$/','',$kata);
		if(preg_match('/([klt]ah|pun)$/',$kata)){ // Jika berupa particles (â€œ-lahâ€?, â€œ-kahâ€?, â€œ-tahâ€? atau â€œ-punâ€?)
			if(preg_match('/([km]u|nya)$/',$kata)){ // Hapus Possesive Pronouns (â€œ-kuâ€?, â€œ-muâ€?, atau â€œ-nyaâ€?)
				$kata = preg_match('/([km]u|nya)$/','',$kata);
				return $kata;
			}
			return $kata;	
		}
		return $kata;	
	}
	return $kataAsal;
}

function Cek_Rule_Precedence($kata){
	if(preg_match('/^(be)[[:alpha:]]+(lah|an)$/',$kata)){ // be- dan -i
		return true;
	}
	if(preg_match('/^(di|([mpt]e))[[:alpha:]]+(i)$/',$kata)){ // di- dan -an	
		return true;	
	}
	return false;
}

// Cek Prefix Disallowed Sufixes (Kombinasi Awalan dan Akhiran yang tidak diizinkan)
function Cek_Prefix_Disallowed_Sufixes($kata){
	if(preg_match('/^(be)[[:alpha:]]+(i)$/',$kata)){ // be- dan -i
		return true;
	}
	if(preg_match('/^(di)[[:alpha:]]+(an)$/',$kata)){ // di- dan -an				
		return true;
		
	}
	if(preg_match('/^(ke)[[:alpha:]]+(i|kan)$/',$kata)){ // ke- dan -i,-kan
		return true;
	}
	if(preg_match('/^(me)[[:alpha:]]+(an)$/',$kata)){ // me- dan -an
		return true;
	}
	if(preg_match('/^(se)[[:alpha:]]+(i|kan)$/',$kata)){ // se- dan -i,-kan
		return true;
	}
	return false;
}

// Hapus Derivation Suffixes (â€œ-iâ€?, â€œ-anâ€? atau â€œ-kanâ€?)
function Del_Derivation_Suffixes($kata){
	$kataAsal = $kata;
	if(preg_match('/(kan)$/',$kata)){ // Cek Suffixes
		$__kata = preg_replace('/(kan)$/','',$kata);		
		if(cekKamus($__kata)){ // Cek Kamus			
			return $__kata;
		}
	}
	if(preg_match('/(an|i)$/',$kata)){ // cek -kan 				
		$__kata__ = preg_replace('/(an|i)$/','',$kata);
		if(cekKamus($__kata__)){ // Cek Kamus
			return $__kata__;
		}
	}
	if(Cek_Prefix_Disallowed_Sufixes($kata)){
		return $kataAsal;
	}
	return $kataAsal;
}

// Hapus Derivation Prefix (â€œdi-â€?, â€œke-â€?, â€œse-â€?, â€œte-â€?, â€œbe-â€?, â€œme-â€?, atau â€œpe-â€?)
function Del_Derivation_Prefix($kata){
	$kataAsal = $kata;	
	/* ------ Tentukan Tipe Awalan ------------*/
	if(preg_match('/^(di|[ks]e)\S{1,}/',$kata)){ // Jika di-,ke-,se-
		$__kata = preg_replace('/^(di|[ks]e)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	if(preg_match('/^([^aiueo])e\\1[aiueo]\S{1,}/i',$kata)){ // aturan  37
		$__kata = preg_replace('/^([^aiueo])e/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	if(preg_match('/^([tmbp]e)\S{1,}/',$kata)){ //Jika awalannya adalah â€œte-â€?, â€œme-â€?, â€œbe-â€?, atau â€œpe-â€?
		/*------------ Awalan â€œbe-â€?, ---------------------------------------------*/
		if(preg_match('/^(be)\S{1,}/',$kata)){ // Jika awalan â€œbe-â€?,
			if(preg_match('/^(ber)[aiueo]\S{1,}/',$kata)){ // aturan 1.
				$__kata = preg_replace('/^(ber)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(ber)/','r',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}				
			}
			
			if(preg_match('/^(ber)[^aiueor][[:alpha:]](?!er)\S{1,}/',$kata)){ //aturan  2.
				$__kata = preg_replace('/^(ber)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(ber)[^aiueor][[:alpha:]]er[aiueo]\S{1,}/',$kata)){ //aturan  3.
				$__kata = preg_replace('/^(ber)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^belajar\S{0,}/',$kata)){ //aturan  4.
				$__kata = preg_replace('/^(bel)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(be)[^aiueolr]er[^aiueo]\S{1,}/',$kata)){ //aturan  5.
				$__kata = preg_replace('/^(be)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
		/*------------end â€œbe-â€?, ---------------------------------------------*/
		/*------------ Awalan â€œte-â€?, ---------------------------------------------*/
		if(preg_match('/^(te)\S{1,}/',$kata)){ // Jika awalan â€œte-â€?,
		
			if(preg_match('/^(terr)\S{1,}/',$kata)){ 
				return $kata;
			}
			if(preg_match('/^(ter)[aiueo]\S{1,}/',$kata)){ // aturan 6.
				$__kata = preg_replace('/^(ter)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(ter)/','r',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(ter)[^aiueor]er[aiueo]\S{1,}/',$kata)){ // aturan 7.
				$__kata = preg_replace('/^(ter)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(ter)[^aiueor](?!er)\S{1,}/',$kata)){ // aturan 8.
				$__kata = preg_replace('/^(ter)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(te)[^aiueor]er[aiueo]\S{1,}/',$kata)){ // aturan 9.
				$__kata = preg_replace('/^(te)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(ter)[^aiueor]er[^aiueo]\S{1,}/',$kata)){ // aturan  35 belum bisa
				$__kata = preg_replace('/^(ter)/','',$kata);
				if(cekKamus($__kata)){			
					 return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
		/*------------end â€œte-â€?, ---------------------------------------------*/
		/*------------ Awalan â€œme-â€?, ---------------------------------------------*/
		if(preg_match('/^(me)\S{1,}/',$kata)){ // Jika awalan â€œme-â€?,
	
			if(preg_match('/^(me)[lrwyv][aiueo]/',$kata)){ // aturan 10
				$__kata = preg_replace('/^(me)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}				
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(mem)[bfvp]\S{1,}/',$kata)){ // aturan 11.
				$__kata = preg_replace('/^(mem)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			/*if(preg_match('/^(mempe)\S{1,}/',$kata)){ // aturan 12
				$__kata = preg_replace('/^(mem)/','pe',$kata);	
				
				if(cekKamus($__kata)){
					
					return $__kata; // Jika ada balik
				}				
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){					
					return $__kata__;
				}				
			}*/
			if (preg_match('/^(mem)((r[aiueo])|[aiueo])\S{1,}/', $kata)){//aturan 13
				$__kata = preg_replace('/^(mem)/','m',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(mem)/','p',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(men)[cdjszt]\S{1,}/',$kata)){ // aturan 14.
				$__kata = preg_replace('/^(men)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if (preg_match('/^(men)[aiueo]\S{1,}/',$kata)){//aturan 15
				$__kata = preg_replace('/^(men)/','n',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(men)/','t',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(meng)[ghqk]\S{1,}/',$kata)){ // aturan 16.
				$__kata = preg_replace('/^(meng)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(meng)[aiueo]\S{1,}/',$kata)){ // aturan 17
				$__kata = preg_replace('/^(meng)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(meng)/','k',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(menge)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(meny)[aiueo]\S{1,}/',$kata)){ // aturan 18.
				$__kata = preg_replace('/^(meny)/','s',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(me)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
		/*------------end â€œme-â€?, ---------------------------------------------*/
		
		/*------------ Awalan â€œpe-â€?, ---------------------------------------------*/
		if(preg_match('/^(pe)\S{1,}/',$kata)){ // Jika awalan â€œpe-â€?,
		
			if(preg_match('/^(pe)[wy]\S{1,}/',$kata)){ // aturan 20.
				$__kata = preg_replace('/^(pe)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}				
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}				
			}
			
			if(preg_match('/^(per)[aiueo]\S{1,}/',$kata)){ // aturan 21
				$__kata = preg_replace('/^(per)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(per)/','r',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(per)[^aiueor][[:alpha:]](?!er)\S{1,}/',$kata)){ // aturan  23
				$__kata = preg_replace('/^(per)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(per)[^aiueor][[:alpha:]](er)[aiueo]\S{1,}/',$kata)){ // aturan  24
				$__kata = preg_replace('/^(per)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pem)[bfv]\S{1,}/',$kata)){ // aturan  25
				$__kata = preg_replace('/^(pem)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pem)(r[aiueo]|[aiueo])\S{1,}/',$kata)){ // aturan  26
				$__kata = preg_replace('/^(pem)/','m',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(pem)/','p',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pen)[cdjzt]\S{1,}/',$kata)){ // aturan  27
				$__kata = preg_replace('/^(pen)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pen)[aiueo]\S{1,}/',$kata)){ // aturan  28
				$__kata = preg_replace('/^(pen)/','n',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(pen)/','t',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(peng)[^aiueo]\S{1,}/',$kata)){ // aturan  29
				$__kata = preg_replace('/^(peng)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(peng)[aiueo]\S{1,}/',$kata)){ // aturan  30
				$__kata = preg_replace('/^(peng)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(peng)/','k',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(penge)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(peny)[aiueo]\S{1,}/',$kata)){ // aturan  31
				$__kata = preg_replace('/^(peny)/','s',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(pe)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pel)[aiueo]\S{1,}/',$kata)){ // aturan  32
				$__kata = preg_replace('/^(pel)/','l',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if (preg_match('/^(pelajar)\S{0,}/',$kata)){
				$__kata = preg_replace('/^(pel)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pe)[^rwylmn]er[aiueo]\S{1,}/',$kata)){ // aturan  33
				$__kata = preg_replace('/^(pe)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pe)[^rwylmn](?!er)\S{1,}/',$kata)){ // aturan  34
				$__kata = preg_replace('/^(pe)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(pe)[^aiueor]er[^aiueo]\S{1,}/',$kata)){ // aturan  36
				$__kata = preg_replace('/^(pe)/','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
	}
		/*------------end â€œpe-â€?, ---------------------------------------------*/
		/*------------ Awalan â€œmemper-â€?, ---------------------------------------------*/
	if(preg_match('/^(memper)\S{1,}/',$kata)){				
		$__kata = preg_replace('/^(memper)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		//*-- Cek luluh -r ----------
		$__kata = preg_replace('/^(memper)/','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end â€œmemper-â€?, ---------------------------------------------*/
	/*------------ Awalan â€œmempel-â€?, ---------------------------------------------*/
	if(preg_match('/^(mempel)\S{1,}/',$kata)){				
		$__kata = preg_replace('/^(mempel)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		//*-- Cek luluh -r ----------
		$__kata = preg_replace('/^(mempel)/','l',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end â€œmempel-â€?, ---------------------------------------------*/
	/*------------awalan  â€œmemter-â€?, ---------------------------------------------*/
	if(preg_match('/^(menter)\S{1,}/',$kata)){				
		$__kata = preg_replace('/^(menter)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		//*-- Cek luluh -r ----------
		$__kata = preg_replace('/^(menter)/','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end â€œmemter-â€?, ---------------------------------------------*/
	/*------------awalan â€œmember-â€?, ---------------------------------------------*/
	if(preg_match('/^(member)\S{1,}/',$kata)){				
		$__kata = preg_replace('/^(member)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		//*-- Cek luluh -r ----------
		$__kata = preg_replace('/^(member)/','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end member-â€?, ---------------------------------------------*/
	/*------------awalan â€œdiper-â€?, ---------------------------------------------*/
	if(preg_match('/^(diper)\S{1,}/',$kata)){			
		$__kata = preg_replace('/^(diper)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		/*-- Cek luluh -r ----------*/
		$__kata = preg_replace('/^(diper)/','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end â€œdiper-â€?, ---------------------------------------------*/
	/*------------awalan â€œditer-â€?, ---------------------------------------------*/
	if(preg_match('/^(diter)\S{1,}/',$kata)){			
		$__kata = preg_replace('/^(diter)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		/*-- Cek luluh -r ----------*/
		$__kata = preg_replace('/^(diter)','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end â€œditer-â€?, ---------------------------------------------*/
	/*------------awalan â€œdipel-â€?, ---------------------------------------------*/
	if(preg_match('/^(dipel)\S{1,}/',$kata)){			
		$__kata = preg_replace('/^(dipel)/','l',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		/*-- Cek luluh -l----------*/
		$__kata = preg_replace('/^(dipel)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end dipel-â€?, ---------------------------------------------*/
	/*------------kata â€œterpelajarâ€?(kasus khusus), ---------------------------------------------*/
	if(preg_match('/terpelajar/',$kata)){			
		$__kata = preg_replace('/terpel/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end â€œterpelajarâ€?-â€?, ---------------------------------------------*/
	/*------------kata seseorang(kasus khusus), ---------------------------------------------*/
	if(preg_match('/seseorang/',$kata)){			
		$__kata = preg_replace('/^(sese)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
	}
	/*------------end seseorang-â€?, ---------------------------------------------*/
	/*------------awalan "diber-"---------------------------------------------*/
	if(preg_match('/^(diber)\S{1,}/',$kata)){			
		$__kata = preg_replace('/^(diber)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		/*-- Cek luluh -l----------*/
		$__kata = preg_replace('/^(diber)/','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end "diber-"---------------------------------------------*/
	/*------------awalan "keber-"---------------------------------------------*/
	if(preg_match('/^(keber)\S{1,}/',$kata)){			
		$__kata = preg_replace('/^(keber)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		/*-- Cek luluh -l----------*/
		$__kata = preg_replace('/^(keber)/','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end "keber-"---------------------------------------------*/
	/*------------awalan "keter-"---------------------------------------------*/
	if(preg_match('/^(keter)\S{1,}/',$kata)){			
		$__kata = preg_replace('/^(keter)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		/*-- Cek luluh -l----------*/
		$__kata = preg_replace('/^(keter)/','r',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end "keter-"---------------------------------------------*/
	/*------------awalan "berke-"---------------------------------------------*/
	if(preg_match('/^(berke)\S{1,}/',$kata)){			
		$__kata = preg_replace('/^(berke)/','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
	}
	/*------------end "berke-"---------------------------------------------*/
	/* --- Cek Ada Tidaknya Prefik/Awalan (â€œdi-â€?, â€œke-â€?, â€œse-â€?, â€œte-â€?, â€œbe-â€?, â€œme-â€?, atau â€œpe-â€?) ------*/
	if(preg_match('/^(di|[kstbmp]e)\S{1,}/',$kata) == FALSE){
		return $kataAsal;
	}
	
	return $kataAsal;
}

function Enhanced_CS($kata){
	
	$kataAsal = $kata;
	
	/* 1. Cek Kata di Kamus jika Ada SELESAI */
	if(cekKamus($kata)){ // Cek Kamus
		return $kata; // Jika Ada kembalikan
	}
	/* 2. Buang Infection suffixes (\-lah", \-kah", \-ku", \-mu", atau \-nya") */
	$kata = Del_Inflection_Suffixes($kata);
		
	/* 3. Buang Derivation suffix (\-i" or \-an") */
	$kata = Del_Derivation_Suffixes($kata);
		
	/* 4. Buang Derivation prefix */
	$kata = Del_Derivation_Prefix($kata);
	
	return $kata;
}


?>
