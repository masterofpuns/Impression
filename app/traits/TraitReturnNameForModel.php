<?php

namespace app;

Trait TraitReturnNameForModel {
    private $name;

    public function getName() {
        $name = [];
        if (!empty($this->firstName)) {
            $name[] = $this->firstName;
        }
        if (!empty($this->lastNamePrefix)) {
            $name[] = $this->lastNamePrefix;
        }
        if (!empty($this->lastName)) {
            $name[] = $this->lastName;
        }

        return implode(' ', $name);
    }

    /**
     *  Function to retrieve the full lastname of a contactperson
     *
     * @return string
     */
    public function getFullLastName()
    {
        $fullLastNameArray = [];

        if (!empty($this->lastNamePrefix)) {
            $fullLastNameArray[] = $this->lastNamePrefix;
        }

        if (!empty($this->lastName)) {
            $fullLastNameArray[] = ucfirst($this->lastName);
        }

        return implode(' ', $fullLastNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson
     *
     * @return string
     */
    public function getFullName()
    {
        $fullNameArray = [];

        if (!empty($this->firstName)) {
            $fullNameArray[] = ucfirst($this->firstName);
        }

        if (!empty($this->getFullLastName())) {

            $fullNameArray[] = $this->getFullLastName();
        }

        return implode(' ', $fullNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson
     *
     * @return string
     */
    public function getFullNameStartingWithLastName()
    {
        $fullNameStartingWithLastNameArray = [];

        if (!empty($this->lastName)) {
            $fullNameStartingWithLastNameArray[] = ucfirst($this->lastName) . ", ";
        }

        if (!empty($this->firstName)) {
            $fullNameStartingWithLastNameArray[] = ucfirst($this->firstName);
        }

        if (!empty($this->lastNamePrefix)) {
            $fullNameStartingWithLastNameArray[] = $this->lastNamePrefix;
        }

        return implode(' ', $fullNameStartingWithLastNameArray);
    }

    public function getFullNameStartingWithLastNameInclInitials()
    {
        $fullNameStartingWithLastNameInclInitialsArray = [];

        if (!empty($this->lastName)) {
            $fullNameStartingWithLastNameInclInitialsArray[] = ucfirst($this->lastName) . ", ";
        }

        if (!empty($this->initials)) {
            $fullNameStartingWithLastNameInclInitialsArray[] = ucfirst($this->initials);
        }

        if (!empty($this->lastNamePrefix)) {
            $fullNameStartingWithLastNameInclInitialsArray[] = $this->lastNamePrefix;
        }

        return implode(' ', $fullNameStartingWithLastNameInclInitialsArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson
     *
     * @return string
     */
    public function getFullNameInclInitials()
    {
        $fullNameArray = [];

        if (!empty($this->initials)) {
            $fullNameArray[] = strtoupper($this->initials);
        }

        if (!empty($this->getFullLastName())) {

            $fullNameArray[] = $this->getFullLastName();
        }

        return implode(' ', $fullNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson including titles
     *
     * @return string
     */
    public function getFullNameInclTitle()
    {
        $fullNameArray = [];

        if (!empty($this->titleBefore)) {
            $fullNameArray[] = $this->titleBefore;
        }

        if (!empty($this->getFullName())) {

            $fullNameArray[] = $this->getFullName();
        }
        if (!empty($this->titleAfter)) {

            $fullNameArray[] = $this->titleAfter;
        }

        return implode(' ', $fullNameArray);
    }

    /**
     *  Function to retrieve the full name of a contactperson including titles and initials instead of firstName
     *
     * @return string
     */
    public function getFullNameInclInitialsAndTitle()
    {
        $fullNameArray = [];

        if (!empty($this->titleBefore)) {
            $fullNameArray[] = $this->titleBefore;
        }

        if (!empty($this->getFullNameInclInitials())) {
            $fullNameArray[] = $this->getFullNameInclInitials();
        }
        if (!empty($this->titleAfter)) {
            $fullNameArray[] = $this->titleAfter;
        }

        return implode(' ', $fullNameArray);
    }
}