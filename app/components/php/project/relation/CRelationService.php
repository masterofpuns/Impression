<?php

namespace app\perree\relation;

use app\m;
use app\perree\bankaccount\MBankAccount;

class CRelationService
{
    /**
     * @param int $relationId
     * @return \app\perree\relation\MCategory
     */
    public function getCategoryForRelation(int $relationId): ?MCategory
    {
        $category = m::app()->db->querySingle(
            "
                SELECT C.* 
                FROM category C
                INNER JOIN relation_category RC ON RC.categoryId = C.id
                WHERE RC.relationId = :relationId
                LIMIT 1
            ",
            [
                ":relationId" => $relationId
            ],
            '\app\perree\relation\MCategory'
        );

        return $category;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MRelationCategory
     */
    public function getRelationCategoryForRelation(int $relationId): ?MRelationCategory
    {
        $category = m::app()->db->querySingle(
            '
                SELECT RC.* 
                FROM relation_category RC                
                WHERE RC.relationId = :relationId
                LIMIT 1
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MRelationCategory'
        );

        return $category;
    }

    /**
     * @param int $relationId
     * @return int $total
     */
    public function getTotalRelationCountForCategory(int $categoryId): ?int
    {
        $result = m::app()->db->query(
            "SELECT COUNT(relationId) as total FROM relation_category WHERE categoryId = :categoryId",
            [":categoryId" => $categoryId]
        );

        $total = 0;
        if ($result->num_rows > 0) {
            $total = $result->fetch_object()->total;
        }

        return $total;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MAdvisor[]
     */
    public function getAdvisorsForRelation(int $relationId): ?array
    {
        $advisors = m::app()->db->query(
            '
                SELECT R.* 
                FROM relation R
                INNER JOIN relation_advisor RA ON RA.advisorRelationId = R.id
                WHERE RA.relationId = :relationId
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MRelation'
        );

        return $advisors;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MPhoneNumber[]
     */
    public function getPhoneNumbersForRelation(int $relationId): ?array
    {
        $phoneNumbers = m::app()->db->query(
            '
                SELECT * 
                FROM phone_number
                WHERE relationId = :relationId
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MPhoneNumber'
        );

        return $phoneNumbers;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MEmailAddress[]
     */
    public function getEmailAddressesForRelation(int $relationId): ?array
    {
        $emailAddresses = m::app()->db->query(
            '
                SELECT * 
                FROM email_address
                WHERE relationId = :relationId
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MEmailAddress'
        );

        return $emailAddresses;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MAddress
     */
    public function getAddressForRelation(int $relationId, string $type): ?MAddress
    {
        $address = m::app()->db->querySingle(
            '
                SELECT *
                FROM address
                WHERE relationId = :relationId
                AND type = :type
                LIMIT 1
            ',
            [
                ':relationId' => $relationId,
                ':type' => $type
            ],
            '\app\perree\relation\MAddress'
        );

        return $address;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MNote[]
     */
    public function getNotesForRelation(int $relationId): ?array
    {
        $notes = m::app()->db->query(
            "
                SELECT N.* 
                FROM note N
                WHERE N.relationId = :relationId
                ORDER BY N.dateTimeCreated DESC
            ",
            [
                ":relationId" => $relationId
            ],
            '\app\perree\relation\MNote'
        );

        return $notes;
    }

    /**
     * @param int $relationId
     * @return \app\perree\bankaccount\MBankAccount
     */
    public function getBankAccountsForRelation(int $relationId): ?array
    {
        $bankAccounts = m::app()->db->query(
            '
                SELECT BA.*
                FROM bank_account BA
                INNER JOIN relation_bank_account RBA ON RBA.bankAccountId = BA.id
                WHERE RBA.relationId = :relationId                
            ',
            [
                ':relationId' => $relationId,
            ],
            '\app\perree\bankaccount\MBankAccount'
        );

        return $bankAccounts;
    }

    /**
     * @param int $relationId
     * @return \app\perree\bankaccount\MBankAccount
     */
    public function getPrimaryBankAccountForRelation(int $relationId): ?MBankAccount
    {
        $bankAccount = m::app()->db->querySingle(
            '
                SELECT BA.*
                FROM bank_account BA
                INNER JOIN relation_bank_account RBA ON RBA.bankAccountId = BA.id
                WHERE RBA.relationId = :relationId
                AND BA.isPrimary = 1
                LIMIT 1
            ',
            [
                ':relationId' => $relationId,
            ],
            '\app\perree\bankaccount\MBankAccount'
        );

        return $bankAccount;
    }

    /**
     * @param int $relationId
     * @return \app\perree\bankaccount\MBankAccount
     */
    public function getRelationBankAccountModelForRelationAndBankAccount(int $relationId, int $bankAccountId): ?MRelationBankAccount
    {
        $relationBankAccount = m::app()->db->querySingle(
            '
                SELECT *
                FROM relation_bank_account
                WHERE relationId = :relationId
                AND bankAccountId = :bankAccountId
            ',
            [
                ':relationId' => $relationId,
                ':bankAccountId' => $bankAccountId,
            ],
            '\app\perree\relation\MRelationBankAccount'
        );

        return $relationBankAccount;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MRelation[]
     */
    public function getContactPersonRelationsForRelation(int $relationId): ?array
    {
        $contactPersonRelations = m::app()->db->query(
            '
                SELECT R.* 
                FROM relation R
                INNER JOIN contact_person CP ON CP.id = R.id
                WHERE CP.parentRelationId = :relationId
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MRelation'
        );

        return $contactPersonRelations;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MRelation[]
     */
    public function getManagerContactPersonRelationsForRelation(int $relationId): ?array
    {
        $contactPersonRelations = m::app()->db->query(
            '
                SELECT R.* 
                FROM relation R
                INNER JOIN contact_person CP ON CP.id = R.id
                WHERE CP.parentRelationId = :relationId
                AND CP.isManager = 1
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MRelation'
        );

        return $contactPersonRelations;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MRelation[]
     */
    public function getProxyContactPersonRelationsForRelation(int $relationId): ?array
    {
        $contactPersonRelations = m::app()->db->query(
            '
                SELECT R.* 
                FROM relation R
                INNER JOIN contact_person CP ON CP.id = R.id
                WHERE CP.parentRelationId = :relationId
                AND CP.isProxy = 1
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MRelation'
        );

        return $contactPersonRelations;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MRelation[]
     */
    public function getParticipatingContactPersonRelationsForRelation(int $relationId): ?array
    {
        $contactPersonRelations = m::app()->db->query(
            '
                SELECT R.* 
                FROM relation R
                INNER JOIN contact_person CP ON CP.id = R.id
                WHERE CP.parentRelationId = :relationId
                AND CP.isParticipant = 1
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MRelation'
        );

        return $contactPersonRelations;
    }

    /**
     * @param int $relationId
     * @return \app\perree\relation\MRelation[]
     */
    public function getNonParticipatingContactPersonRelationsForRelation(int $relationId): ?array
    {
        $contactPersonRelations = m::app()->db->query(
            '
                SELECT R.* 
                FROM relation R
                INNER JOIN contact_person CP ON CP.id = R.id
                WHERE CP.parentRelationId = :relationId
                AND CP.isParticipant != 1
            ',
            [
                ':relationId' => $relationId
            ],
            '\app\perree\relation\MRelation'
        );

        return $contactPersonRelations;
    }

    public function findRelation($string)
    {
        $relations = m::app()->db->query(
            "
                SELECT *
                FROM relation
                WHERE nameSortable LIKE :relationName
                OR search LIKE :relationName
            ",
            [":relationName" => '%' . $string . '%'],
            '\app\perree\relation\MRelation'
        );
        return $relations;
    }
}