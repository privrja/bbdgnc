<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Smiles\Enum\VertexStateEnum;

class Node {

    /** @var Element atom */
    private $atom;

    /** @var int[] $arDigits */
    private $arDigits = [];

    /** @var int $invariant */
    private $invariant;

    /** @var CangenStructure $cangenStructure */
    private $cangenStructure;

    /** @var bool $inRing */
    private $inRing = false;

    /** @var Bond[] */
    private $arBonds = array();

    /**
     * @var int $vertexState
     * @see VertexStateEnum
     */
    private $vertexState = VertexStateEnum::NOT_FOUND;

    /**
     * Node constructor.
     * @param Element $atom
     * @param array $arBounds
     */
    public function __construct(Element $atom, array $arBounds = []) {
        $this->atom = $atom;
        $this->arBonds = $arBounds;
        $this->cangenStructure = new CangenStructure();
    }

    public function actualBindings() {
        $actualBindings = 0;
        foreach ($this->arBonds as $bond) {
            $actualBindings += $bond->getBondType();
        }
        return $actualBindings;
    }

    public function hydrogensCount() {
        return $this->atom->getHydrogensCount($this->actualBindings());
    }

    public function addBond(Bond $bond) {
        $this->arBonds[] = $bond;
    }

    public function computeInvariants() {
        $this->invariant = "";
        $this->invariant .= sizeof($this->arBonds);
        $this->invariant .= $this->actualBindingsWithZero();
        $this->invariant .= $this->protonNumber();
        $this->invariant .= $this->atom->getCharge()->getSignValue();
        $this->invariant .= $this->atom->getCharge()->getChargeSize();
        $this->invariant .= $this->hydrogensCount();
    }

    private function protonNumber() {
        return $this->addZero($this->atom->getProtons());
    }

    private function actualBindingsWithZero() {
        return $this->addZero($this->actualBindings());
    }

    private function addZero($number) {
        return $number < 10 ? '0' . $number : $number;
    }

    /**
     * @return Element
     */
    public function getAtom(): Element {
        return $this->atom;
    }

    /**
     * @return mixed
     */
    public function getInvariant() {
        return $this->invariant;
    }

    /**
     * @return Bond[]
     */
    public function getBonds(): array {
        return $this->arBonds;
    }

    /**
     * @param $invariant
     */
    public function setInvariant($invariant) {
        $this->invariant = $invariant;
    }

    /**
     * @return CangenStructure
     */
    public function getCangenStructure(): CangenStructure {
        return $this->cangenStructure;
    }

    /**
     * @param CangenStructure $cangenStructure
     */
    public function setCangenStructure(CangenStructure $cangenStructure): void {
        $this->cangenStructure = $cangenStructure;
    }

    /**
     * @return int
     */
    public function getVertexState(): int {
        return $this->vertexState;
    }

    /**
     * @param int $vertexState
     */
    public function setVertexState(int $vertexState): void {
        $this->vertexState = $vertexState;
    }

    /**
     * @return int[]
     */
    public function getDigits(): array {
        return $this->arDigits;
    }

    /**
     * @param int[] $arDigits
     */
    public function setDigits(array $arDigits): void {
        $this->arDigits = $arDigits;
    }

    /**
     * Add digit to arDigits
     * @param int $digit
     */
    public function addDigit(int $digit): void {
        $this->arDigits[] = $digit;
    }

    public function deleteDigit(int $digit): void {
        $arDigitsLength = sizeof($this->arDigits);
        for ($index = 0; $index < $arDigitsLength; ++$index) {
            if ($digit === $this->arDigits[$index]) {
                unset($this->arDigits[$index]);
                return;
            }
        }
    }

    /**
     * Check if digits are empty
     * @return bool
     */
    public function isDigitsEmpty() {
        return empty($this->arDigits);
    }

    /**
     * @return bool
     */
    public function isInRing(): bool {
        return $this->inRing;
    }

    /**
     * @param bool $inRing
     */
    public function setInRing(bool $inRing): void {
        $this->inRing = $inRing;
    }

}
