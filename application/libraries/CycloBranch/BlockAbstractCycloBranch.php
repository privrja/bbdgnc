<?php

use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\TransportObjects\BlockTO;

class BlockAbstractCycloBranch extends AbstractCycloBranch {

    // TODO to slucovani je jen u bloku?
    // TODO je to vzdy v tom souboru ulozeny jako slouceny? pres ty lomitka?
    protected function parseLine(string $line) {
        $arItems = preg_split('/\t/', $line);
        if (empty($arItems)) {
            return;
        }
        $itemsLength = sizeof($arItems);
        $arBlocks = [];
        // TODO $itemsLength !== 5 ??? what to do
        var_dump($arItems);
        $arNames = explode('/', $arItems[0]);
        $length = sizeof($arNames);
        // TODO jde to takhle?
        $arSmiles = [$length];
        $arAcronyms = explode('/', $arItems[1]);
        // TODO jak to je sreferencema, vzdy jen jedna? nebo i vice?
        $arReference = explode('/', $arItems[4]);
        for ($index = 0; $index < $length; ++$index) {
            // TODO co kdyz tam in neni? co vraci explode?, arReference need to string
            $arTmp = explode('in', $arReference);
            if (empty($arTmp)) {
                $arSmiles[] = '';
            } else {
                // TODO remove spaces -> nejspis je oriznout o posledni znak bude stacit
                $arSmiles[] = $arTmp[0];
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