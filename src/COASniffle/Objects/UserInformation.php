<?php


    namespace COASniffle\Objects;


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
         * @var mixed
         */
        public $Avatar;

        /**
         * The user's Email Address
         *
         * @var mixed
         */
        public $EmailAddress;

        /**
         * The user's Personal Information
         *
         * @var mixed
         */
        public $PersonalInformation;
    }