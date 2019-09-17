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
	 * @var string|null
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
	 * @var int
	 */
	private $avatar = 0;

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
	public function getStorage()
	{
		return $this->storage;
	}
	
	public function getServerUrl()
	{
		return $this->url;
	}
	
	public function setServerUrl($a_url)
	{
		$this->url = $a_url;
	}

	/**
	 * Get direct link to group managment
	 */
	public function getGroupFolderLink()
	{
		$group_url = str_replace('services', '', $this->getServerUrl());
		$group_url = ilUtil::removeTrailingPathSeparators($group_url);
		return $group_url.'/user/cms/groupfolder.htm';
	}

	public function setWebstartUrl($a_url)
	{
		$this->webstart = $a_url;
	}

	public function getWebstartUrl()
	{
		return $this->webstart;
	}
	
	public function setAdminUser($a_admin)
	{
		$this->admin = $a_admin;
	}
	
	public function getAdminUser()
	{
		return $this->admin;
	}
	
	public function setAdminPass($a_pass)
	{
		$this->pass = $a_pass;
	}
	
	public function getAdminPass()
	{
		return $this->pass;
	}

	public function setCustomer($a_cust)
	{
		$this->customer = $a_cust;
	}

	public function getCustomer()
	{
		return $this->customer;
	}

	public function useLdap($a_stat)
	{
		$this->use_ldap = $a_stat;
	}

	public function isLdapUsed()
	{
		return $this->use_ldap;
	}

	public function enableCafe($a_stat)
	{
		$this->enable_cafe = $a_stat;
	}

	public function isCafeEnabled()
	{
		return $this->enable_cafe;
	}

	public function enableStandardRoom($a_stat)
	{
		$this->enable_standard_room = $a_stat;
	}

	public function isStandardRoomEnabled()
	{
		return $this->enable_standard_room;
	}
	
	public function enableContentAdministration($a_stat)
	{
		$this->enable_content = $a_stat;
	}
	
	public function isContentAdministrationEnabled()
	{
		return $this->enable_content;
	}

	public function setUserPrefix($a_prefix)
	{
		$this->user_prefix = $a_prefix;
	}

	public function getUserPrefix()
	{
		return $this->user_prefix;
	}

	public function setStandardGracePeriodBefore($a_val)
	{
		$this->grace_period_before = $a_val;
	}

	public function getStandardGracePeriodBefore()
	{
		return $this->grace_period_before;
	}

	public function setStandardGracePeriodAfter($a_val)
	{
		$this->grace_period_after = $a_val;
	}

	public function getStandardGracePeriodAfter()
	{
		return $this->grace_period_after;
	}

	public function enableAvatar($a_stat)
	{
		$this->avatar = $a_stat;
	}

	public function isAvatarEnabled()
	{
		return (bool) $this->avatar;
	}
	
	public function setMTOMCert($a_cert)
	{
		$this->mtom_cert = $a_cert;
	}
	
	public function getMTOMCert()
	{
		return $this->mtom_cert;
	}

	public function enableLearningProgress($a_stat)
	{
		$this->enable_learning_progress = $a_stat;
	}

	public function isLearningProgressEnabled()
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
		$this->getStorage()->set(self::SETTING_LDAP, $this->isLdapUsed());
		$this->getStorage()->set(self::SETTING_CAFE, $this->isCafeEnabled());
		$this->getStorage()->set(self::SETTING_CONTENT,$this->isContentAdministrationEnabled());
		$this->getStorage()->set(self::SETTING_STD_ROOM,(int) $this->isStandardRoomEnabled());
		$this->getStorage()->set(self::SETTING_WEBSTART,$this->getWebstartUrl());
		$this->getStorage()->set(self::SETTING_UPREFIX,$this->getUserPrefix());
		$this->getStorage()->set(self::SETTING_GRACE_PERIOD_BEFORE,$this->getStandardGracePeriodBefore());
		$this->getStorage()->set(self::SETTING_GRACE_PERIOD_AFTER,$this->getStandardGracePeriodAfter());
		$this->getStorage()->set(self::SETTING_AVATAR,(int) $this->isAvatarEnabled());
		$this->getStorage()->set(self::SETTING_MTOM_CERT,$this->getMTOMCert());
		$this->getStorage()->set(self::SETTING_PHONE_CONFERENCE, (int) $this->isPhoneConferenceEnabled());
		$this->getStorage()->set(self::SETTING_PHONE_DIAL_OUT, (int) $this->isPhoneDialOutEnabled());
		$this->getStorage()->set(self::SETTING_PHONE_DIAL_OUT_PARTICIPANTS,$this->isPhoneDialOutParticipantsEnabled());
		$this->getStorage()->set(self::SETTING_MOBILE, (int) $this->isMobileAccessEnabled());
		$this->getStorage()->set(self::SETTING_RECORDER, (int) $this->isSessionRecorderEnabled());
		$this->getStorage()->set(self::SETTING_LEARNING_PROGRESS, $this->isLearningProgressEnabled());

	}

	/**
	 * Read settings
	 */
	protected function read()
	{
		$this->setServerUrl($this->getStorage()->get(self::SETTING_SERVER, $this->url));
		$this->setAdminUser($this->getStorage()->get(self::SETTING_ADMIN, $this->admin));
		$this->setAdminPass($this->getStorage()->get(self::SETTING_PASS, $this->pass));
		$this->setCustomer($this->getStorage()->get(self::SETTING_CUSTOMER, $this->customer));
		$this->useLdap($this->getStorage()->get(self::SETTING_LDAP, $this->use_ldap));
		$this->enableCafe($this->getStorage()->get(self::SETTING_CAFE, $this->enable_cafe));
		$this->enableContentAdministration($this->getStorage()->get(self::SETTING_CONTENT,$this->enable_content));
		$this->enableStandardRoom($this->getStorage()->get(self::SETTING_STD_ROOM, $this->enable_standard_room));
		$this->setWebstartUrl($this->getStorage()->get(self::SETTING_WEBSTART,$this->webstart));
		$this->setUserPrefix($this->getStorage()->get(self::SETTING_UPREFIX,$this->user_prefix));
		$this->setStandardGracePeriodBefore($this->getStorage()->get(self::SETTING_GRACE_PERIOD_BEFORE,$this->grace_period_before));
		$this->setStandardGracePeriodAfter($this->getStorage()->get(self::SETTING_GRACE_PERIOD_AFTER, $this->grace_period_after));
		$this->enableAvatar($this->getStorage()->get(self::SETTING_AVATAR, $this->avatar));
		$this->setMTOMCert($this->getStorage()->get(self::SETTING_MTOM_CERT,$this->mtom_cert));
		$this->enablePhoneConference($this->getStorage()->get(self::SETTING_PHONE_CONFERENCE, $this->isPhoneConferenceEnabled()));
		$this->enablePhoneDialOut($this->getStorage()->get(self::SETTING_PHONE_DIAL_OUT, $this->isPhoneDialOutEnabled()));
		$this->enablePhoneDialOutParticipants($this->getStorage()->get(self::SETTING_PHONE_DIAL_OUT_PARTICIPANTS, $this->isPhoneDialOutParticipantsEnabled()));
		$this->enableMobileAccess($this->getStorage()->get(self::SETTING_MOBILE,$this->isMobileAccessEnabled()));
		$this->enableSessionRecorder($this->getStorage()->get(self::SETTING_RECORDER,$this->isSessionRecorderEnabled()));
		$this->enableLearningProgress($this->getStorage()->get(self::SETTING_LEARNING_PROGRESS, $this->isLearningProgressEnabled()));
	}

	/**
	 * @return bool
	 */
	public function isSessionRecorderEnabled()
	{
		return $this->session_recorder;
	}

	/**
	 * @param bool $a_session_recorder
	 */
	public function enableSessionRecorder($a_session_recorder)
	{
		$this->session_recorder = $a_session_recorder;
	}

	/**
	 * @return bool
	 */
	public function isMobileAccessEnabled()
	{
		return $this->mobile_access_enabled;
	}

	/**
	 * @param bool $a_mobile_access
	 */
	public function enableMobileAccess($a_mobile_access)
	{
		$this->mobile_access_enabled = $a_mobile_access;
	}

	/**
	 * @param bool $a_phone_enabled
	 */
	public function enablePhoneOptions($a_phone_enabled)
	{
		$this->phone_enabled = $a_phone_enabled;
	}

	/**
	 * @return bool
	 */
	public function arePhoneOptionsEnabled()
	{
		return
			$this->isPhoneConferenceEnabled() ||
			$this->isPhoneDialOutEnabled() ||
			$this->isPhoneDialOutParticipantsEnabled();
	}

	/**
	 * @return bool
	 */
	public function isPhoneConferenceEnabled()
	{
		return $this->phone_conference;
	}

	/**
	 * @param $a_stat
	 */
	public function enablePhoneConference($a_stat)
	{
		$this->phone_conference = $a_stat;
	}

	/**
	 * @return bool
	 */
	public function isPhoneDialOutEnabled()
	{
		return $this->phone_dial_out;
	}

	/**
	 * @param $a_stat
	 */
	public function enablePhoneDialOut($a_stat)
	{
		$this->phone_dial_out = $a_stat;
	}

	/**
	 * @return bool
	 */
	public function isPhoneDialOutParticipantsEnabled()
	{
		return $this->phone_dial_out_part;
	}

	/**
	 * @param $a_stat
	 */
	public function enablePhoneDialOutParticipants($a_stat)
	{
		$this->phone_dial_out_part = $a_stat;
	}

}
?>
