<?php

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\TransportObjects\BlockTO;

class BlockCycloBranch extends AbstractCycloBranch {

    // TODO to slucovani je jen u bloku?
    // TODO je to vzdy v tom souboru ulozeny jako slouceny? pres ty lomitka?
    protected function parseLine(string $line) {
        $arItems = preg_split('/\t/', $line);
        if (empty($arItems)) {
            return;
        }
        $itemsLength = sizeof($arItems);
        // TODO $itemsLength !== 5 ??? what to do
        var_dump($arItems);
        $arNames = explode('/', $arItems[0]);
        $length = sizeof($arNames);
        $arSmiles = [];
        $arAcronyms = explode('/', $arItems[1]);
        // TODO jak to je s referencema, vzdy jen jedna? nebo i vice?
        $arReference = explode('/', $arItems[4]);
        for ($index = 0; $index < $length; ++$index) {
            // TODO co kdyz tam in neni? co vraci explode?, arReference need to string
            $arTmp = explode('in', $arReference[$index]);
            var_dump($arTmp);
            var_dump($arReference);
            if (empty($arTmp) || $arTmp[0] === $arReference[$index]) {
                $arSmiles[] = '';
            } else {
                $smiles = substr($arTmp[0], 0, -1);
                $arSmiles[] = FormulaHelper::genericSmiles($smiles);
            }
        }

        // TODO reference

        for ($index = 0; $index < $length; ++$index) {
            // TODO zjistit jak to je se SMILES v souboru, pravidla na parsovani
            $blockTO = new BlockTO(0, $arNames[$index], $arAcronyms[$index], $arSmiles[$index], ComputeEnum::UNIQUE_SMILES);
            // TODO save to DB mozna by se hodilo vytvorit transakci kvuli chybe na radku asi neukladat ty co budou dobre
        }

    }

    public function export() {
        // TODO: Implement export() method.
    }
}