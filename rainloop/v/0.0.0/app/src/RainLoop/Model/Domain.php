<?php

namespace RainLoop\Model;

use MailSo\Net\Enumerations\ConnectionSecurityType;

class Domain
{
	const DEFAULT_FORWARDED_FLAG = '$Forwarded';

	/**
	 * @var string
	 */
	private $sName;

	/**
	 * @var string
	 */
	private $sIncHost;

	/**
	 * @var int
	 */
	private $iIncPort;

	/**
	 * @var int
	 */
	private $iIncSecure;

	/**
	 * @var bool
	 */
	private $bIncShortLogin;

	/**
	 * @var string
	 */
	private $sOutHost;

	/**
	 * @var int
	 */
	private $iOutPort;

	/**
	 * @var int
	 */
	private $iOutSecure;

	/**
	 * @var bool
	 */
	private $bOutShortLogin;

	/**
	 * @var bool
	 */
	private $bOutAuth;

	/**
	 * @var bool
	 */
	private $bOutUsePhpMail;

	/**
	 * @var bool
	 */
	private $bUseSieve;

	/**
	 * @var bool
	 */
	private $bUseImapServerForSieve;

	/**
	 * @var string
	 */
	private $sSieveHost;

	/**
	 * @var int
	 */
	private $iSievePort;

	/**
	 * @var int
	 */
	private $iSieveSecure;

	/**
	 * @var string
	 */
	private $sWhiteList;

	/**
	 * @param string $sName
	 * @param string $sIncHost
	 * @param int $iIncPort
	 * @param int $iIncSecure
	 * @param bool $bIncShortLogin
	 * @param bool $bUseSieve
	 * @param bool $bUseImapServerForSieve
	 * @param string $sSieveHost
	 * @param int $iSievePort
	 * @param int $iSieveSecure
	 * @param string $sOutHost
	 * @param int $iOutPort
	 * @param int $iOutSecure
	 * @param bool $bOutShortLogin
	 * @param bool $bOutAuth
	 * @param bool $bOutUsePhpMail = false
	 * @param string $sWhiteList = ''
	 */
	private function __construct($sName,
		$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
		$bUseSieve, $bUseImapServerForSieve, $sSieveHost, $iSievePort, $iSieveSecure,
		$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail = false,
		$sWhiteList = '')
	{
		$this->sName = $sName;
		$this->sIncHost = $sIncHost;
		$this->iIncPort = $iIncPort;
		$this->iIncSecure = $iIncSecure;
		$this->bIncShortLogin = $bIncShortLogin;

		$this->sOutHost = $sOutHost;
		$this->iOutPort = $iOutPort;
		$this->iOutSecure = $iOutSecure;
		$this->bOutShortLogin = $bOutShortLogin;
		$this->bOutAuth = $bOutAuth;
		$this->bOutUsePhpMail = $bOutUsePhpMail;

		$this->bUseSieve = $bUseSieve;
		$this->bUseImapServerForSieve = $bUseImapServerForSieve;
		$this->sSieveHost = $sSieveHost;
		$this->iSievePort = $iSievePort;
		$this->iSieveSecure = $iSieveSecure;

		$this->sWhiteList = \trim($sWhiteList);
	}

	/**
	 * @param string $sName
	 * @param string $sIncHost
	 * @param int $iIncPort
	 * @param int $iIncSecure
	 * @param bool $bIncShortLogin
	 * @param bool $bUseSieve
	 * @param bool $bUseImapServerForSieve
	 * @param string $sSieveHost
	 * @param int $iSievePort
	 * @param int $iSieveSecure
	 * @param string $sOutHost
	 * @param int $iOutPort
	 * @param int $iOutSecure
	 * @param bool $bOutShortLogin
	 * @param bool $bOutAuth
	 * @param bool $bOutUsePhpMail = false
	 * @param string $sWhiteList = ''
	 *
	 * @return \RainLoop\Model\Domain
	 */
	public static function NewInstance($sName,
		$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
		$bUseSieve, $bUseImapServerForSieve, $sSieveHost, $iSievePort, $iSieveSecure,
		$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
		$sWhiteList = '')
	{
		return new self($sName,
			$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
			$bUseSieve, $bUseImapServerForSieve, $sSieveHost, $iSievePort, $iSieveSecure,
			$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
			$sWhiteList);
	}

	/**
	 * @param string $sName
	 * @param array $aDomain
	 *
	 * @return \RainLoop\Model\Domain|null
	 */
	public static function NewInstanceFromDomainConfigArray($sName, $aDomain)
	{
		$oDomain = null;

		if (0 < \strlen($sName) && \is_array($aDomain) && 0 < \strlen($aDomain['imap_host']) && 0 < \strlen($aDomain['imap_port']))
		{
			$sIncHost = (string) $aDomain['imap_host'];
			$iIncPort = (int) $aDomain['imap_port'];
			$iIncSecure = self::StrConnectionSecurityTypeToCons(
				!empty($aDomain['imap_secure']) ? $aDomain['imap_secure'] : '');

			$sSieveHost = empty($aDomain['sieve_host']) ? '' : (string) $aDomain['sieve_host'];
			$iSievePort = empty($aDomain['sieve_port']) ? 2000 : (int) $aDomain['sieve_port'];
			$iSieveSecure = self::StrConnectionSecurityTypeToCons(
				!empty($aDomain['sieve_secure']) ? $aDomain['sieve_secure'] : '');

			$sOutHost = empty($aDomain['smtp_host']) ? '' : (string) $aDomain['smtp_host'];
			$iOutPort = empty($aDomain['smtp_port']) ? 25 : (int) $aDomain['smtp_port'];
			$iOutSecure = self::StrConnectionSecurityTypeToCons(
				!empty($aDomain['smtp_secure']) ? $aDomain['smtp_secure'] : '');

			$bOutAuth = isset($aDomain['smtp_auth']) ? (bool) $aDomain['smtp_auth'] : true;
			$bOutUsePhpMail = isset($aDomain['smtp_php_mail']) ? (bool) $aDomain['smtp_php_mail'] : false;
			$sWhiteList = (string) (isset($aDomain['white_list']) ? $aDomain['white_list'] : '');

			$bUseSieve = isset($aDomain['sieve_use']) ? (bool) $aDomain['sieve_use'] : false;
			$bUseImapServerForSieve = isset($aDomain['sieve_use_imap_server']) ? (bool) $aDomain['sieve_use_imap_server'] : true;

			$bIncShortLogin = isset($aDomain['imap_short_login']) ? (bool) $aDomain['imap_short_login'] : false;
			$bOutShortLogin = isset($aDomain['smtp_short_login']) ? (bool) $aDomain['smtp_short_login'] : false;

			$oDomain = self::NewInstance($sName,
				$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
				$bUseSieve, $bUseImapServerForSieve, $sSieveHost, $iSievePort, $iSieveSecure,
				$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
				$sWhiteList);
		}

		return $oDomain;
	}

	/**
	 * @param string $sStr
	 *
	 * @return string
	 */
	private function encodeIniString($sStr)
	{
		return str_replace('"', '\\"', $sStr);
	}

	public function Normalize()
	{
		$this->sIncHost = \trim($this->sIncHost);
		$this->sSieveHost = \trim($this->sSieveHost);
		$this->sOutHost = \trim($this->sOutHost);
		$this->sWhiteList = \trim($this->sWhiteList);

		if ($this->iIncPort <= 0)
		{
			$this->iIncPort = 143;
		}

		if ($this->iSievePort <= 0)
		{
			$this->iSievePort = 2000;
		}

		if ($this->iOutPort <= 0)
		{
			$this->iOutPort = 25;
		}
	}

	/**
	 * @return string
	 */
	public function ToIniString()
	{
		$this->Normalize();
		return \implode("\n", array(
			'imap_host = "'.$this->encodeIniString($this->sIncHost).'"',
			'imap_port = '.$this->iIncPort,
			'imap_secure = "'.self::ConstConnectionSecurityTypeToStr($this->iIncSecure).'"',
			'imap_short_login = '.($this->bIncShortLogin ? 'On' : 'Off'),
			'sieve_use = '.($this->bUseSieve ? 'On' : 'Off'),
			'sieve_use_imap_server = '.($this->bUseImapServerForSieve ? 'On' : 'Off'),
			'sieve_host = "'.$this->encodeIniString($this->sSieveHost).'"',
			'sieve_port = '.$this->iSievePort,
			'sieve_secure = "'.self::ConstConnectionSecurityTypeToStr($this->iSieveSecure).'"',
			'smtp_host = "'.$this->encodeIniString($this->sOutHost).'"',
			'smtp_port = '.$this->iOutPort,
			'smtp_secure = "'.self::ConstConnectionSecurityTypeToStr($this->iOutSecure).'"',
			'smtp_short_login = '.($this->bOutShortLogin ? 'On' : 'Off'),
			'smtp_auth = '.($this->bOutAuth ? 'On' : 'Off'),
			'smtp_php_mail = '.($this->bOutUsePhpMail ? 'On' : 'Off'),
			'white_list = "'.$this->encodeIniString($this->sWhiteList).'"'
		));
	}

	/**
	 * @param string $sType
	 *
	 * @return int
	 */
	public static function StrConnectionSecurityTypeToCons($sType)
	{
		$iSecurityType = ConnectionSecurityType::NONE;
		switch (strtoupper($sType))
		{
			case 'SSL':
				$iSecurityType = ConnectionSecurityType::SSL;
				break;
			case 'TLS':
				$iSecurityType = ConnectionSecurityType::STARTTLS;
				break;
		}
		return $iSecurityType;
	}

	/**
	 * @param int $iSecurityType
	 *
	 * @return string
	 */
	public static function ConstConnectionSecurityTypeToStr($iSecurityType)
	{
		$sType = 'None';
		switch ($iSecurityType)
		{
			case ConnectionSecurityType::SSL:
				$sType = 'SSL';
				break;
			case ConnectionSecurityType::STARTTLS:
				$sType = 'TLS';
				break;
		}

		return $sType;
	}

	/**
	 * @param string $sIncHost
	 * @param int $iIncPort
	 * @param int $iIncSecure
	 * @param bool $bIncShortLogin
	 * @param bool $bUseSieve
	 * @param bool $bUseImapServerForSieve
	 * @param string $sOutHost
	 * @param int $iOutPort
	 * @param int $iOutSecure
	 * @param bool $bOutShortLogin
	 * @param bool $bOutAuth
	 * @param bool $bOutUsePhpMail = false
	 * @param string $sWhiteList = ''
	 *
	 * @return \RainLoop\Model\Domain
	 */
	public function UpdateInstance(
		$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
		$bUseSieve, $bUseImapServerForSieve, $sSieveHost, $iSievePort, $iSieveSecure,
		$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
		$sWhiteList = '')
	{
		$this->sIncHost = \MailSo\Base\Utils::IdnToAscii($sIncHost);
		$this->iIncPort = $iIncPort;
		$this->iIncSecure = $iIncSecure;
		$this->bIncShortLogin = $bIncShortLogin;

		$this->bUseSieve = $bUseSieve;
		$this->bUseImapServerForSieve = $bUseImapServerForSieve;
		$this->sSieveHost = \MailSo\Base\Utils::IdnToAscii($sSieveHost);
		$this->iSievePort = $iSievePort;
		$this->iSieveSecure = $iSieveSecure;

		$this->sOutHost = \MailSo\Base\Utils::IdnToAscii($sOutHost);
		$this->iOutPort = $iOutPort;
		$this->iOutSecure = $iOutSecure;
		$this->bOutShortLogin = $bOutShortLogin;
		$this->bOutAuth = $bOutAuth;
		$this->bOutUsePhpMail = $bOutUsePhpMail;

		$this->sWhiteList = \trim($sWhiteList);

		return $this;
	}

	/**
	 * @return string
	 */
	public function Name()
	{
		return $this->sName;
	}

	/**
	 * @return string
	 */
	public function IncHost()
	{
		return $this->sIncHost;
	}

	/**
	 * @return int
	 */
	public function IncPort()
	{
		return $this->iIncPort;
	}

	/**
	 * @return int
	 */
	public function IncSecure()
	{
		return $this->iIncSecure;
	}

	/**
	 * @return bool
	 */
	public function IncShortLogin()
	{
		return $this->bIncShortLogin;
	}

	/**
	 * @return bool
	 */
	public function UseSieve()
	{
		return $this->bUseSieve;
	}

	/**
	 * @return bool
	 */
	public function UseImapServerForSieve()
	{
		return $this->bUseImapServerForSieve;
	}

	/**
	 * @return string
	 */
	public function SieveHost()
	{
		return $this->sSieveHost;
	}

	/**
	 * @return int
	 */
	public function SievePort()
	{
		return $this->iSievePort;
	}

	/**
	 * @return int
	 */
	public function SieveSecure()
	{
		return $this->iSieveSecure;
	}

	/**
	 * @return string
	 */
	public function OutHost()
	{
		return $this->sOutHost;
	}

	/**
	 * @return int
	 */
	public function OutPort()
	{
		return $this->iOutPort;
	}

	/**
	 * @return int
	 */
	public function OutSecure()
	{
		return $this->iOutSecure;
	}

	/**
	 * @return bool
	 */
	public function OutShortLogin()
	{
		return $this->bOutShortLogin;
	}

	/**
	 * @return bool
	 */
	public function OutAuth()
	{
		return $this->bOutAuth;
	}

	/**
	 * @return bool
	 */
	public function OutUsePhpMail()
	{
		return $this->bOutUsePhpMail;
	}

	/**
	 * @return string
	 */
	public function WhiteList()
	{
		return $this->sWhiteList;
	}

	/**
	 * @param string $sEmail
	 * @param string $sLogin = ''
	 *
	 * @return bool
	 */
	public function ValidateWhiteList($sEmail, $sLogin = '')
	{
		$sW = \trim($this->sWhiteList);
		if (0 < strlen($sW))
		{
			$sEmail = \MailSo\Base\Utils::IdnToUtf8($sEmail, true);
			$sLogin = \MailSo\Base\Utils::IdnToUtf8($sLogin);

			$sW = \preg_replace('/([^\s]+)@[^\s]*/', '$1', $sW);
			$sW = ' '.\trim(\preg_replace('/[\s;,\r\n\t]+/', ' ', $sW)).' ';

			$sUserPart = \MailSo\Base\Utils::GetAccountNameFromEmail(0 < \strlen($sLogin) ? $sLogin : $sEmail);
			return false !== \strpos($sW, ' '.$sUserPart.' ');
		}

		return true;
	}

	/**
	 * @param bool $bAjax = false
	 *
	 * @return array
	 */
	public function ToSimpleJSON($bAjax = false)
	{
		return array(
			'Name' => $bAjax ? \MailSo\Base\Utils::IdnToUtf8($this->Name()) : $this->Name(),
			'IncHost' => $bAjax ? \MailSo\Base\Utils::IdnToUtf8($this->IncHost()) : $this->IncHost(),
			'IncPort' => $this->IncPort(),
			'IncSecure' => $this->IncSecure(),
			'IncShortLogin' => $this->IncShortLogin(),
			'UseSieve' => $this->UseSieve(),
			'UseImapServerForSieve' => $this->UseImapServerForSieve(),
			'SieveHost' => $bAjax ? \MailSo\Base\Utils::IdnToUtf8($this->SieveHost()) : $this->SieveHost(),
			'SievePort' => $this->SievePort(),
			'SieveSecure' => $this->SieveSecure(),
			'OutHost' => $bAjax ? \MailSo\Base\Utils::IdnToUtf8($this->OutHost()) : $this->OutHost(),
			'OutPort' => $this->OutPort(),
			'OutSecure' => $this->OutSecure(),
			'OutShortLogin' => $this->OutShortLogin(),
			'OutAuth' => $this->OutAuth(),
			'OutUsePhpMail' => $this->OutUsePhpMail(),
			'WhiteList' => $this->WhiteList()
		);
	}
}