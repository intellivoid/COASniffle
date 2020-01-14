<?php


    namespace COASniffle\Objects;


    use COASniffle\Objects\UserInformation\Avatar;
    use COASniffle\Objects\UserInformation\EmailAddress;
    use COASniffle\Objects\UserInformation\PersonalInformation;

    /**
     * Class UserInformation
     * @package COASniffle\Objects
     */
    class UserInformation
    {
        /**
         * Unique Tag ID for the user
         *
         * @var int
         */
        public $Tag;

        /**
         * The public ID for the user
         *
         * @var string
         */
        public $PublicID;

        /**
         * The user's Username
         *
         * @var string
         */
        public $Username;

        /**
         * The avatar's that are available with this user
         *
         * @var Avatar
         */
        public $Avatar;

        /**
         * The user's Email Address
         *
         * @var EmailAddress
         */
        public $EmailAddress;

        /**
         * The user's Personal Information
         *
         * @var PersonalInformation
         */
        public $PersonalInformation;

        /**
         * Returns an array which represents this
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'tag' => (int)$this->Tag,
                'public_id' => $this->PublicID,
                'username' => $this->Username,
                'avatar' => $this->Avatar->toArray(),
                'email_address' => $this->EmailAddress->toArray(),
                'personal_information' => $this->PersonalInformation->toArray()
            );
        }
    }