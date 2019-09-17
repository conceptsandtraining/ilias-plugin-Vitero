<?php

declare(strict_types=1);

/**
 * Global vitero settings
 * @author Stefan Meyer <smeyer.ilias@gmx.de>
 * $Id: class.ilViteroSettings.php 36242 2012-08-15 13:00:21Z smeyer $
 */
class ilViteroSettings
{
	const PHONE_CONFERENCE = 1;
	const PHONE_DIAL_OUT = 2;
	const PHONE_DIAL_OUT_PART = 3;

	const SETTING_SERVER = 'server';
	const SETTING_ADMIN = 'admin';
	const SETTING_PASS = 'pass';
	const SETTING_CUSTOMER = 'customer';
	const SETTING_LDAP = 'ldap';
	const SETTING_CAFE = 'cafe';
	const SETTING_CONTENT = 'content';
	const SETTING_STD_ROOM = 'std_room';
	const SETTING_WEBSTART = 'webstart';
	const SETTING_UPREFIX = 'uprefix';
	const SETTING_GRACE_PERIOD_BEFORE = 'grace_period_before';
	const SETTING_GRACE_PERIOD_AFTER = 'grace_period_after';
	const SETTING_AVATAR = 'avatar';
	const SETTING_MTOM_CERT = 'mtom_cert';
	const SETTING_PHONE_CONFERENCE = 'phone_conference';
	const SETTING_PHONE_DIAL_OUT = 'phone_dial_out';
	const SETTING_PHONE_DIAL_OUT_PARTICIPANTS = 'phone_dial_out_participants';
	const SETTING_MOBILE = 'mobile';
	const SETTING_RECORDER = 'recorder';
	const SETTING_LEARNING_PROGRESS = 'learning_progress';

	/**
	 * @var ilViteroSettings|null
	 */
	private static $instance = null;

	/**
	 * @var ilSetting|null
	 */
	private $storage = null;

	/**
	 * @var string
	 */
	private $url = 'http://yourserver.de/vitero/services';

	/**
	 * @var string
	 */
	private $webstart = 'http://yourserver.de/vitero/start.htm';

	/**
	 * @var string
	 */
	private $admin = '';

	/**
	 * @var string
	 */
	private $pass = '';

	/**
	 * @var int|null
	 */
	private $customer = NULL;

	/**
	 * @var bool
	 */
	private $use_ldap = false;

	/**
	 * @var bool
	 */
	private $enable_cafe = false;

	/**
	 * @var bool
	 */
	private $enable_content = false;

	/**
	 * @var bool
	 */
	private $enable_standard_room = true;

	/**
	 * @var string
	 */
	private $user_prefix = 'il_';

	/**
	 * @var bool
	 */
	private $avatar = false;

	/**
	 * @var string
	 */
	private $mtom_cert = '';

	/**
	 * @var bool
	 */
	private $phone_conference = false;

	/**
	 * @var bool
	 */
	private $phone_dial_out = false;

	/**
	 * @var bool
	 */
	private $phone_dial_out_part = false;

	/**
	 * @var bool
	 */
	private $mobile_access_enabled = false;

	/**
	 * @var bool
	 */
	private $session_recorder = false;

	/**
	 * @var bool
	 */
	private $enable_learning_progress = false;

	/**
	 * @var int
	 */
	private $grace_period_before = 15;

	/**
	 * @var int
	 */
	private $grace_period_after = 15;

	/**
	 * @var bool
	 */
	private $phone_enabled = false;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->storage = new ilSetting('vitero_config');
		$this->read();
	}
	
	/**
	 * Get singelton instance
	 * 
	 * @return ilViteroSettings
	 */
	public static function getInstance()
	{
		if(self::$instance)
		{
			return self::$instance;
		}
		return self::$instance = new ilViteroSettings();
	}

	/**
	 * Get storage
	 * @return ilSetting
	 */
	public function getStorage(): ilSetting
	{
		return $this->storage;
	}
	
	public function getServerUrl() : string
	{
		return $this->url;
	}
	
	public function setServerUrl(string $a_url)
	{
		$this->url = $a_url;
	}

	/**
	 * Get direct link to group managment
	 */
	public function getGroupFolderLink() : string
	{
		$group_url = str_replace('services', '', $this->getServerUrl());
		$group_url = ilUtil::removeTrailingPathSeparators($group_url);
		return $group_url.'/user/cms/groupfolder.htm';
	}

	public function setWebstartUrl(string $a_url)
	{
		$this->webstart = $a_url;
	}

	public function getWebstartUrl() : string
	{
		return $this->webstart;
	}
	
	public function setAdminUser(string $a_admin)
	{
		$this->admin = $a_admin;
	}
	
	public function getAdminUser() : string
	{
		return $this->admin;
	}
	
	public function setAdminPass(string $a_pass)
	{
		$this->pass = $a_pass;
	}
	
	public function getAdminPass() : string
	{
		return $this->pass;
	}

	public function setCustomer(int $a_cust = null)
	{
		$this->customer = $a_cust;
	}

	public function getCustomer() : int
	{
		return $this->customer;
	}

	public function useLdap(bool $a_stat)
	{
		$this->use_ldap = $a_stat;
	}

	public function isLdapUsed() : bool
	{
		return $this->use_ldap;
	}

	public function enableCafe(bool $a_stat)
	{
		$this->enable_cafe = $a_stat;
	}

	public function isCafeEnabled() : bool
	{
		return $this->enable_cafe;
	}

	public function enableStandardRoom(bool $a_stat)
	{
		$this->enable_standard_room = $a_stat;
	}

	public function isStandardRoomEnabled() : bool
	{
		return $this->enable_standard_room;
	}
	
	public function enableContentAdministration(bool $a_stat)
	{
		$this->enable_content = $a_stat;
	}
	
	public function isContentAdministrationEnabled() : bool
	{
		return $this->enable_content;
	}

	public function setUserPrefix(string $a_prefix)
	{
		$this->user_prefix = $a_prefix;
	}

	public function getUserPrefix() : string
	{
		return $this->user_prefix;
	}

	public function setStandardGracePeriodBefore(int $a_val)
	{
		$this->grace_period_before = $a_val;
	}

	public function getStandardGracePeriodBefore() : int
	{
		return $this->grace_period_before;
	}

	public function setStandardGracePeriodAfter(int $a_val)
	{
		$this->grace_period_after = $a_val;
	}

	public function getStandardGracePeriodAfter() : int
	{
		return $this->grace_period_after;
	}

	public function enableAvatar(bool $a_stat)
	{
		$this->avatar = $a_stat;
	}

	public function isAvatarEnabled() : bool
	{
		return $this->avatar;
	}
	
	public function setMTOMCert(string $a_cert)
	{
		$this->mtom_cert = $a_cert;
	}
	
	public function getMTOMCert() : string
	{
		return $this->mtom_cert;
	}

	public function enableLearningProgress(bool $a_stat)
	{
		$this->enable_learning_progress = $a_stat;
	}

	public function isLearningProgressEnabled() : bool
	{
		return $this->enable_learning_progress;
	}


	/**
	 * Save settings
	 */
	public function save()
	{
		$this->getStorage()->set(self::SETTING_SERVER, $this->getServerUrl());
		$this->getStorage()->set(self::SETTING_ADMIN, $this->getAdminUser());
		$this->getStorage()->set(self::SETTING_PASS, $this->getAdminPass());
		$this->getStorage()->set(self::SETTING_CUSTOMER, $this->getCustomer());
		$this->getStorage()->set(self::SETTING_LDAP, (int)$this->isLdapUsed());
		$this->getStorage()->set(self::SETTING_CAFE, (int)$this->isCafeEnabled());
		$this->getStorage()->set(self::SETTING_CONTENT, (int)$this->isContentAdministrationEnabled());
		$this->getStorage()->set(self::SETTING_STD_ROOM, (int)$this->isStandardRoomEnabled());
		$this->getStorage()->set(self::SETTING_WEBSTART, $this->getWebstartUrl());
		$this->getStorage()->set(self::SETTING_UPREFIX, $this->getUserPrefix());
		$this->getStorage()->set(self::SETTING_GRACE_PERIOD_BEFORE, $this->getStandardGracePeriodBefore());
		$this->getStorage()->set(self::SETTING_GRACE_PERIOD_AFTER, $this->getStandardGracePeriodAfter());
		$this->getStorage()->set(self::SETTING_AVATAR, (int)$this->isAvatarEnabled());
		$this->getStorage()->set(self::SETTING_MTOM_CERT, $this->getMTOMCert());
		$this->getStorage()->set(self::SETTING_PHONE_CONFERENCE, (int) $this->isPhoneConferenceEnabled());
		$this->getStorage()->set(self::SETTING_PHONE_DIAL_OUT, (int) $this->isPhoneDialOutEnabled());
		$this->getStorage()->set(self::SETTING_PHONE_DIAL_OUT_PARTICIPANTS, (int)$this->isPhoneDialOutParticipantsEnabled());
		$this->getStorage()->set(self::SETTING_MOBILE, (int) $this->isMobileAccessEnabled());
		$this->getStorage()->set(self::SETTING_RECORDER, (int) $this->isSessionRecorderEnabled());
		$this->getStorage()->set(self::SETTING_LEARNING_PROGRESS, (int)$this->isLearningProgressEnabled());

	}

	/**
	 * Read settings
	 */
	protected function read()
	{
		$this->setServerUrl($this->getStorage()->get(self::SETTING_SERVER, $this->url));
		$this->setAdminUser($this->getStorage()->get(self::SETTING_ADMIN, $this->admin));
		$this->setAdminPass($this->getStorage()->get(self::SETTING_PASS, $this->pass));
		$this->setCustomer((int)$this->getStorage()->get(self::SETTING_CUSTOMER, $this->customer));
		$this->useLdap((bool)$this->getStorage()->get(self::SETTING_LDAP, $this->use_ldap));
		$this->enableCafe((bool)$this->getStorage()->get(self::SETTING_CAFE, $this->enable_cafe));
		$this->enableContentAdministration((bool)$this->getStorage()->get(self::SETTING_CONTENT,$this->enable_content));
		$this->enableStandardRoom((bool)$this->getStorage()->get(self::SETTING_STD_ROOM, $this->enable_standard_room));
		$this->setWebstartUrl($this->getStorage()->get(self::SETTING_WEBSTART,$this->webstart));
		$this->setUserPrefix($this->getStorage()->get(self::SETTING_UPREFIX,$this->user_prefix));
		$this->setStandardGracePeriodBefore((int)$this->getStorage()->get(self::SETTING_GRACE_PERIOD_BEFORE,$this->grace_period_before));
		$this->setStandardGracePeriodAfter((int)$this->getStorage()->get(self::SETTING_GRACE_PERIOD_AFTER, $this->grace_period_after));
		$this->enableAvatar((bool)$this->getStorage()->get(self::SETTING_AVATAR, $this->avatar));
		$this->setMTOMCert($this->getStorage()->get(self::SETTING_MTOM_CERT,$this->mtom_cert));
		$this->enablePhoneConference((bool)$this->getStorage()->get(self::SETTING_PHONE_CONFERENCE, $this->isPhoneConferenceEnabled()));
		$this->enablePhoneDialOut((bool)$this->getStorage()->get(self::SETTING_PHONE_DIAL_OUT, $this->isPhoneDialOutEnabled()));
		$this->enablePhoneDialOutParticipants((bool)$this->getStorage()->get(self::SETTING_PHONE_DIAL_OUT_PARTICIPANTS, $this->isPhoneDialOutParticipantsEnabled()));
		$this->enableMobileAccess((bool)$this->getStorage()->get(self::SETTING_MOBILE,$this->isMobileAccessEnabled()));
		$this->enableSessionRecorder((bool)$this->getStorage()->get(self::SETTING_RECORDER,$this->isSessionRecorderEnabled()));
		$this->enableLearningProgress((bool)$this->getStorage()->get(self::SETTING_LEARNING_PROGRESS, $this->isLearningProgressEnabled()));
	}

	public function isSessionRecorderEnabled() : bool
	{
		return $this->session_recorder;
	}

	public function enableSessionRecorder(bool $a_session_recorder)
	{
		$this->session_recorder = $a_session_recorder;
	}

	public function isMobileAccessEnabled() : bool
	{
		return $this->mobile_access_enabled;
	}

	public function enableMobileAccess(bool $a_mobile_access)
	{
		$this->mobile_access_enabled = $a_mobile_access;
	}

	public function enablePhoneOptions(bool $a_phone_enabled)
	{
		$this->phone_enabled = $a_phone_enabled;
	}

	/**
	 * @return bool
	 */
	public function arePhoneOptionsEnabled() : bool
	{
		return
			$this->isPhoneConferenceEnabled() ||
			$this->isPhoneDialOutEnabled() ||
			$this->isPhoneDialOutParticipantsEnabled();
	}

	public function isPhoneConferenceEnabled() : bool
	{
		return $this->phone_conference;
	}

	public function enablePhoneConference(bool $a_stat)
	{
		$this->phone_conference = $a_stat;
	}

	public function isPhoneDialOutEnabled() : bool
	{
		return $this->phone_dial_out;
	}

	public function enablePhoneDialOut(bool $a_stat)
	{
		$this->phone_dial_out = $a_stat;
	}

	public function isPhoneDialOutParticipantsEnabled() : bool
	{
		return $this->phone_dial_out_part;
	}

	public function enablePhoneDialOutParticipants(bool $a_stat)
	{
		$this->phone_dial_out_part = $a_stat;
	}

}
?>
