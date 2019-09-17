<?php

include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");
 
/**
 * Vitero configuration
 *
 * @author Stefan Meyer <smeyer.ilias@gmx.de>
 * @version $Id: class.ilViteroConfigGUI.php 44271 2013-08-19 14:40:33Z smeyer $
 *
 */
class ilViteroConfigGUI extends ilPluginConfigGUI
{
	const F_SERVER = 'server';
	const F_ADMIN = 'admin';
	const F_PASS = 'pass';
	const F_CUSTOMER = 'customer';
	const F_LDAP = 'ldap';
	const F_CAFE = 'cafe';
	const F_CONTENT = 'content';
	const F_STD_ROOM = 'std_room';
	const F_WEBSTART = 'webstart';
	const F_UPREFIX = 'uprefix';
	const F_GRACE_PERIOD_BEFORE = 'grace_period_before';
	const F_GRACE_PERIOD_AFTER = 'grace_period_after';
	const F_AVATAR = 'avatar';
	const F_MTOM_CERT = 'mtom_cert';
	const F_PHONE_CONFERENCE = 'phone_conference';
	const F_PHONE_DIAL_OUT = 'phone_dial_out';
	const F_PHONE_DIAL_OUT_PARTICIPANTS = 'phone_dial_out_participants';
	const F_MOBILE = 'mobile';
	const F_RECORDER = 'recorder';
	const F_LEARNING_PROGRESS = 'learning_progress';

	/**
	* Handles all commmands, default is "configure"
	*/
	public function performCommand($cmd)
	{
		global $ilTabs;

		$ilTabs->addTab(
			'settings',
			ilViteroPlugin::getInstance()->txt('tab_settings'),
			$GLOBALS['ilCtrl']->getLinkTarget($this,'configure')
		);

		if(ilViteroSettings::getInstance()->getCustomer())
		{
			$ilTabs->addTab(
				'appointments',
				ilViteroPlugin::getInstance()->txt('tab_appointments'),
				$GLOBALS['ilCtrl']->getLinkTarget($this,'listAppointments')
			);
		}

		switch ($cmd)
		{
			case "configure":
			case "save":
			case 'listAppointments':
				$this->$cmd();
				break;

		}
	}

	/**
	 * Configure screen
	 */
	public function configure()
	{
		global $tpl, $ilTabs;

		$ilTabs->activateTab('settings');

		$form = $this->initConfigurationForm();
		$tpl->setContent($form->getHTML());

		#$soap = new ilViteroUserSoapConnector();
		#$soap->call();

	}
	
	/**
	 * Init configuration form.
	 *
	 * @return ilPropertyFormGUI form
	 */
	public function initConfigurationForm()
	{
		global $lng, $ilCtrl;

		$pl = $this->getPluginObject();

		$this->getPluginObject()->includeClass('class.ilViteroSettings.php');
		$settings = ilViteroSettings::getInstance();


		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();
		$form->setTitle($this->getPluginObject()->txt('vitero_plugin_configuration'));
		$form->setFormAction($ilCtrl->getFormAction($this));
		$form->addCommandButton('save', $lng->txt('save'));
		$form->setShowTopButtons(false);

		// Server url
		$uri = new ilTextInputGUI(
			$this->getPluginObject()->txt('server_uri'),
			self::F_SERVER
		);
		$uri->setRequired(true);
		$uri->setSize(80);
		$uri->setMaxLength(512);
		$uri->setValue($settings->getServerUrl());
		$form->addItem($uri);

		// Admin user
		$admin = new ilTextInputGUI(
			$this->getPluginObject()->txt('admin_user'),
			self::F_ADMIN
		);
		$admin->setRequired(true);
		$admin->setSize(16);
		$admin->setMaxLength(128);
		$admin->setValue($settings->getAdminUser());
		$form->addItem($admin);

		// Admin pass
		$pass = new ilPasswordInputGUI(
			$this->getPluginObject()->txt('admin_pass'),
			self::F_PASS
		);
		$pass->setSkipSyntaxCheck(true);
		//$pass->setRequired(true);
		$pass->setRetype(true);
		$pass->setSize(12);
		$pass->setMaxLength(32);
		//$pass->setValue($settings->getAdminPass());
		$form->addItem($pass);


		// Customer id
		$cid = new ilNumberInputGUI(
			$this->getPluginObject()->txt('customer_id'),
			self::F_CUSTOMER
		);
		$cid->setSize(3);
		$cid->setMaxLength(9);
		$cid->setRequired(true);
		$cid->setMinValue(1);
		$cid->setValue($settings->getCustomer());
		$form->addItem($cid);

		// Webstart
		$ws = new ilTextInputGUI(
			$this->getPluginObject()->txt('webstart_url'),
			self::F_WEBSTART
		);
		$ws->setRequired(true);
		$ws->setSize(80);
		$ws->setMaxLength(512);
		$ws->setValue($settings->getWebstartUrl());
		$form->addItem($ws);

		//  Client Section
		$client = new ilFormSectionHeaderGUI();
		$client->setTitle($this->getPluginObject()->txt('client_settings'));
		$form->addItem($client);


		// cafe
		$cafe = new ilCheckboxInputGUI(
			$this->getPluginObject()->txt('cafe_setting'),
			self::F_CAFE
		);
		$cafe->setInfo($this->getPluginObject()->txt('cafe_setting_info'));
		$cafe->setValue(1);
		$cafe->setChecked($settings->isCafeEnabled());
		$form->addItem($cafe);

		// Standard room
		$standard = new ilCheckboxInputGUI(
			$this->getPluginObject()->txt('standard_room'),
			self::F_STD_ROOM
		);
		$standard->setInfo(
			$this->getPluginObject()->txt('standard_room_info')
		);
		$standard->setValue(1);
		$standard->setChecked($settings->isStandardRoomEnabled());
		$form->addItem($standard);

		// phone settings
		// -> phone conference
		$conference = new ilCheckboxInputGUI(
			ilViteroPlugin::getInstance()->txt('settings_phone_conference'),
			self::F_PHONE_CONFERENCE
		);
		$conference->setInfo(
			ilViteroPlugin::getInstance()->txt('settings_phone_conference_info')
		);
		$conference->setChecked($settings->isPhoneConferenceEnabled());
		$form->addItem($conference);

		// -> phone dial-out
		$dial_out = new ilCheckboxInputGUI(
			ilViteroPlugin::getInstance()->txt('settings_phone_dial_out'),
			self::F_PHONE_DIAL_OUT
		);
		$dial_out->setInfo(
			ilViteroPlugin::getInstance()->txt('settings_phone_dial_out_info')
		);
		$dial_out->setChecked($settings->isPhoneDialOutEnabled());
		$form->addItem($dial_out);

		// -> phone dial-out participant
		$dial_out_phone_part = new ilDclCheckboxInputGUI(
			ilViteroPlugin::getInstance()->txt('settings_phone_dial_out_part'),
			self::F_PHONE_DIAL_OUT_PARTICIPANTS
		);
		$dial_out_phone_part->setInfo(
			ilViteroPlugin::getInstance()->txt('settings_phone_dial_out_part_info')
		);
		$dial_out_phone_part->setChecked($settings->isPhoneDialOutParticipantsEnabled());
		$form->addItem($dial_out_phone_part);

		// mobile access
		$mobile = new ilCheckboxInputGUI(
			$this->getPluginObject()->txt('settings_mobile'),
			self::F_MOBILE
		);
		$mobile->setInfo(
			$this->getPluginObject()->txt('settings_mobile_info')
		);
		$mobile->setValue(1);
		$mobile->setChecked($settings->isMobileAccessEnabled());
		$form->addItem($mobile);

		// recorder access
		$recorder = new ilCheckboxInputGUI(
			$this->getPluginObject()->txt('settings_recorder'),
			self::F_RECORDER
		);
		$recorder->setInfo(
			$this->getPluginObject()->txt('settings_recorder_info')
		);
		$recorder->setValue(1);
		$recorder->setChecked($settings->isSessionRecorderEnabled());
		$form->addItem($recorder);

		// content
		$content = new ilCheckboxInputGUI(
			$this->getPluginObject()->txt('content_admin'),
			self::F_CONTENT
		);
		$content->setInfo($this->getPluginObject()->txt('content_admin_info'));
		$content->setValue(1);
		$content->setChecked($settings->isContentAdministrationEnabled());
		$form->addItem($content);


		// ldap
		$ldap = new ilCheckboxInputGUI(
			$this->getPluginObject()->txt('ldap_setting'),
			self::F_LDAP
		);
		$ldap->setInfo($this->getPluginObject()->txt('ldap_setting_info'));
		$ldap->setValue(1);
		$ldap->setChecked($settings->isLdapUsed());
		#$form->addItem($ldap);

		// userprefix
		$prefix = new ilTextInputGUI($this->getPluginObject()->txt('uprefix'), self::F_UPREFIX);
		$prefix->setInfo($this->getPluginObject()->txt('uprefix_info'));
		$prefix->setSize(6);
		$prefix->setMaxLength(16);
		$prefix->setValue($settings->getUserPrefix());
		$form->addItem($prefix);

		// avatar
		$ava = new ilCheckboxInputGUI($this->getPluginObject()->txt('avatar'), self::F_AVATAR);
		$ava->setValue(1);
		$ava->setChecked($settings->isAvatarEnabled());

		$ava->setInfo($this->getPluginObject()->txt('avatar_info'));
		$form->addItem($ava);

		/*
		if(!class_exists('WSMessage'))
		{
			$ava->setDisabled(true);
			$ava->setAlert($this->getPluginObject()->txt('avatar_warning'));
		}

		$cert = new ilTextInputGUI($this->getPluginObject()->txt('mtom_cert'),self::SETTING_MTOM_CERT);
		$cert->setValue($settings->getMTOMCert());
		$cert->setSize(100);
		$cert->setMaxLength(512);
		$cert->setInfo($this->getPluginObject()->txt('mtom_cert_info'));
		if(!class_exists('WSMessage'))
		{
			$cert->setDisabled(true);
		}
		$ava->addSubItem($cert);
		*/

		// grace period before
		$gpb = new ilSelectInputGUI($this->getPluginObject()->txt('std_grace_period_before'), self::F_GRACE_PERIOD_BEFORE);
		$gpb->setInfo($this->getPluginObject()->txt('std_grace_period_before_info'));
		$gpb->setOptions(
			array(
				0 => '0 min',
				15 => '15 min',
				30 => '30 min',
				45 => '45 min',
				60 => '1 h'
			)
		);
		$gpb->setValue($settings->getStandardGracePeriodBefore());
		$form->addItem($gpb);

		// grace period after
		$gpa = new ilSelectInputGUI($this->getPluginObject()->txt('std_grace_period_after'), self::F_GRACE_PERIOD_AFTER);
		$gpa->setInfo($this->getPluginObject()->txt('std_grace_period_after_info'));
		$gpa->setOptions(
			array(
				0 => '0 min',
				15 => '15 min',
				30 => '30 min',
				45 => '45 min',
				60 => '1 h'
			)
		);
		$gpa->setValue($settings->getStandardGracePeriodAfter());
		$form->addItem($gpa);

		//TODO FINISH THIS CHECKBOX HERE
		//Step 1 check if lp enabled in administration globaly
		//Step 2 check Statistik-Modul
		if($this->hasAccessToLearningProgress())
		{
			$learning_progress = new ilCheckboxInputGUI($this->getPluginObject()->txt('activate_learning_progress'), self::F_LEARNING_PROGRESS);
			$learning_progress->setChecked($settings->isLearningProgressEnabled());

			$learning_progress->setInfo($this->getPluginObject()->txt('activate_learning_progress_info'));
			$form->addItem($learning_progress);
		}

		return $form;
	}

	/**
	 * Save form input (currently does not save anything to db)
	 *
	 */
	public function save()
	{
		global $tpl, $lng, $ilCtrl, $ilTabs;

		$ilTabs->activateTab('settings');

		$pl = $this->getPluginObject();

		$form = $this->initConfigurationForm();
		if($form->checkInput())
		{
			$this->getPluginObject()->includeClass('class.ilViteroSettings.php');
			$settings = ilViteroSettings::getInstance();
			$settings->setServerUrl($form->getInput(self::F_SERVER));
			$settings->setAdminUser($form->getInput(self::F_ADMIN));
			if(strlen($form->getInput(self::F_PASS)))
			{
				$settings->setAdminPass($form->getInput(self::F_PASS));
			}
			$settings->setCustomer((int)$form->getInput(self::F_CUSTOMER));
			$settings->useLdap((bool)$form->getInput(self::F_LDAP));
			$settings->enableCafe((bool)$form->getInput(self::F_CAFE));
			$settings->enableContentAdministration((bool)$form->getInput(self::F_CONTENT));
			$settings->enableStandardRoom((bool)$form->getInput(self::F_STD_ROOM));
			$settings->setWebstartUrl($form->getInput(self::F_WEBSTART));
			$settings->setUserPrefix($form->getInput(self::F_UPREFIX));
			$settings->setStandardGracePeriodBefore($form->getInput(self::F_GRACE_PERIOD_BEFORE));
			$settings->setStandardGracePeriodAfter($form->getInput(self::F_GRACE_PERIOD_AFTER));
			$settings->enableAvatar((bool)$form->getInput(self::F_AVATAR));
			//$settings->setMTOMCert($form->getInput(self::SETTING_MTOM_CERT));
			$settings->enablePhoneConference((bool)$form->getInput(self::F_PHONE_CONFERENCE));
			$settings->enablePhoneDialOut((bool)$form->getInput(self::F_PHONE_DIAL_OUT));
			$settings->enablePhoneDialOutParticipants((bool)$form->getInput(self::F_PHONE_DIAL_OUT_PARTICIPANTS));
			$settings->enableMobileAccess((bool)$form->getInput(self::F_MOBILE));
			$settings->enableSessionRecorder((bool)$form->getInput(self::F_RECORDER));
			$settings->enableLearningProgress((bool)$form->getInput(self::F_LEARNING_PROGRESS));
			$settings->save();

			ilUtil::sendSuccess($lng->txt('settings_saved'), true);
			$ilCtrl->redirect($this, "configure");
		}
		else
		{
			ilUtil::sendFailure($lng->txt('err_check_input'),true);
			$form->setValuesByPost();
			$tpl->setContent($form->getHtml());
		}
	}

	protected function listAppointments()
	{
		global $ilTabs, $tpl;

		$ilTabs->activateTab('appointments');

		$table = new ilViteroBookingTableGUI($this,'listAppointments');
		$table->setAdminTable(true);
		$table->setEditable(false);
		$table->init();

		$start = new ilDateTime(time(),IL_CAL_UNIX);
		$start->increment(ilDateTime::HOUR,-1);
		$end = clone $start;
		$end->increment(IL_CAL_YEAR,2);

		try {
			$table->parseAdminTable(
				$start,
				$end
			);
		}
		catch(ilViteroConnectorException $e)
		{
			ilUtil::sendFailure($e->getViteroMessage(),true);
			return false;
		}
		$tpl->setContent($table->getHTML());
	}

	/**
	 * TODO: MISSING THE STATISTIC MODULE!
	 */
	private function hasAccessToLearningProgress()
	{
		if(ilObjUserTracking::_enabledLearningProgress() && ilViteroUtils::hasCustomerMonitoringMode())
		{
			return true;
		}

		return false;
	}

}
?>