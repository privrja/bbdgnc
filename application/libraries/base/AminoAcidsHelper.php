<?php

namespace Bbdgnc\Base;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ModificationTO;

/**
 * Class AminoAcidsHelper
 * Helper for creating base structures
 * @package Bbdgnc\Base
 */
class AminoAcidsHelper {

    const DATABASE = ServerEnum::PUBCHEM;

    /**
     * @return array of base 20 amino acids
     */
    public static function getAminoAcids() {
        $blocks = [];
        $blocks[] = BlockTO::createBlock('Phenylalanine', 'Phe', 'C9H9NO', 147.06841399999998998, '', 'C1=CC=C(C=C1)CC(C(=O)O)N', 'NC(CC1=CC=CC=C1)C(O)=O', self::DATABASE, '6140')->asEntity();
        $blocks[] = BlockTO::createBlock('Alanine', 'Ala', 'C3H5NO', 71.037114000000002532, '', 'CC(C(=O)O)N', 'CC(N)C(O)=O', self::DATABASE, '5950')->asEntity();
        $blocks[] = BlockTO::createBlock('Leucine', 'Leu', 'C6H11NO', 113.08406399999999792, '', 'CC(C)CC(C(=O)O)N', 'CC(C)CC(N)C(O)=O', self::DATABASE, '6106')->asEntity();
        $blocks[] = BlockTO::createBlock('Isoleucine', 'Ile', 'C6H11NO', 113.08406399999999792, '', 'CCC(C)C(C(=O)O)N', 'CCC(C)C(N)C(O)=O', self::DATABASE, '6306')->asEntity();
        $blocks[] = BlockTO::createBlock('Proline', 'Pro', 'C5H7NO', 97.052763999999996256, '', 'C1CC(NC1)C(=O)O', 'OC(=O)C1CCCN1', self::DATABASE, '145742')->asEntity();
        $blocks[] = BlockTO::createBlock('Valine', 'Val', 'C5H9NO', 99.068414000000004195, '', 'CC(C)C(C(=O)O)N', 'CC(C)C(N)C(O)=O', self::DATABASE, '6287')->asEntity();
        $blocks[] = BlockTO::createBlock('Arginine', 'Arg', 'C6H12N4O', 156.10111100000000306, 'NH3;CH2N2', 'C(CC(C(=O)O)N)CN=C(N)N', 'NC(CCCN=C(N)N)C(O)=O', self::DATABASE, '6322')->asEntity();
        $blocks[] = BlockTO::createBlock('Asparagine', 'Asn', 'C4H6N2O2', 114.04292700000000593, 'NH3;CONH', 'C(C(C(=O)O)N)C(=O)N', 'NC(CC(N)=O)C(O)=O', self::DATABASE, '6267')->asEntity();
        $blocks[] = BlockTO::createBlock('Aspartic acid', 'Asp', 'C4H5NO3', 115.02694300000000282, 'H2O;CO2', 'C(C(C(=O)O)N)C(=O)O', 'NC(CC(O)=O)C(O)=O', self::DATABASE, '5960')->asEntity();
        $blocks[] = BlockTO::createBlock('Cysteine', 'Cys', 'C3H5NOS', 103.00918400000000473, 'H2S', 'C(C(C(=O)O)N)S', 'NC(CS)C(O)=O', self::DATABASE, '5862')->asEntity();
        $blocks[] = BlockTO::createBlock('Glutamine', 'Gln', 'C5H8N2O2', 128.05857800000001134, 'NH3;CONH', 'C(CC(=O)N)C(C(=O)O)N', 'NC(CCC(N)=O)C(O)=O', self::DATABASE, '5961')->asEntity();
        $blocks[] = BlockTO::createBlock('Glutamic acid', 'Glu', 'C5H7NO3', 129.04259300000001076, 'H2O;CO2', 'C(CC(=O)O)C(C(=O)O)N', 'NC(CCC(O)=O)C(O)=O', self::DATABASE, '33032')->asEntity();
        $blocks[] = BlockTO::createBlock('Glycine', 'Gly', 'C2H3NO', 57.021464000000001703, '', 'C(C(=O)O)N', 'NCC(O)=O', self::DATABASE, '750')->asEntity();
        $blocks[] = BlockTO::createBlock('Histidine', 'His', 'C6H7N3O', 137.0589119999999923, '', 'C1=C(NC=N1)CC(C(=O)O)N', 'NC(CC1=CN=CN1)C(O)=O', self::DATABASE, '6274')->asEntity();
        $blocks[] = BlockTO::createBlock('Lysine', 'Lys', 'C6H12N2O', 128.09496300000000701, 'NH3', 'C(CCN)CC(C(=O)O)N', 'NCCCCC(N)C(O)=O', self::DATABASE, '5962')->asEntity();
        $blocks[] = BlockTO::createBlock('Methionine', 'Met', 'C5H9NOS', 131.04048499999998967, '', 'CSCCC(C(=O)O)N', 'CSCCC(N)C(O)=O', self::DATABASE, '6137')->asEntity();
        $blocks[] = BlockTO::createBlock('Serine', 'Ser', 'C3H5NO2', 87.032027999999996836, 'H2O;CH2O', 'C(C(C(=O)O)N)O', 'NC(CO)C(O)=O', self::DATABASE, '5951')->asEntity();
        $blocks[] = BlockTO::createBlock('Threonine', 'Thr', 'C4H7NO2', 101.04767800000000476, 'H2O;CH2CH2O', 'CC(C(C(=O)O)N)O', 'CC(O)C(N)C(O)=O', self::DATABASE, '6288')->asEntity();
        $blocks[] = BlockTO::createBlock('Tryptophan', 'Trp', 'C11H10N2O', 186.07931300000001328, '', 'C1=CC=C2C(=C1)C(=CN2)CC(C(=O)O)N', 'NC(CC1=CNC2=CC=CC=C12)C(O)=O', self::DATABASE, '6305')->asEntity();
        $blocks[] = BlockTO::createBlock('Tyrosine', 'Tyr', 'C9H9NO2', 163.06332900000001018, 'H2O', 'C1=CC(=CC=C1CC(C(=O)O)N)O', 'NC(CC1=CC=C(O)C=C1)C(O)=O', self::DATABASE, '6057')->asEntity();
        return $blocks;
    }

    /**
     * @return array of base 4 modifications
     */
    public static function getDefaultModifications(): array {
        $modifications = [];
        $modifications[] = ModificationTO::createModification('Acetyl', 'H2C2O', 42.0105646863, true, false)->asEntity();
        $modifications[] = ModificationTO::createModification('Amidated', 'HNO-1', -0.9840155848, false, true)->asEntity();
        $modifications[] = ModificationTO::createModification('Ethanolamine', 'H5C2N', 43.0421991657, false, true)->asEntity();
        $modifications[] = ModificationTO::createModification('Formyl', 'CO', 27.9949146221, true, false)->asEntity();
        return $modifications;
    }

}
