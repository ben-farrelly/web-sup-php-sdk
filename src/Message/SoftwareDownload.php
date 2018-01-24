<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * User's downloaded softwares.
 * Find information about available options
 * for `software`, `OS` and `version` at:
 * https://github.com/serato/web-sup-php-app
 */
class SoftwareDownload extends AbstractMessage
{
    const SOFTWARE_PARAM_NAME = 'software';
    const SOFTWARE_VER_PARAM_NAME = 'version';
    const SOFTWARE_OS_PARAM_NAME = 'os';

    /**
     * Set the name of the software download
     *
     * @param string    $software   Software name
     * @return self
     */
    public function setSoftwareName($software)
    {
        return $this->setParam(self::SOFTWARE_PARAM_NAME, $software);
    }

    /**
     * Get the name of the software download
     *
     * @return string
     */
    public function getSoftwareName()
    {
        return $this->getParam(self::SOFTWARE_PARAM_NAME);
    }

    /**
     * Set the operating system of the software download
     *
     * @param string    $os    Operating system
     * @return self
     */
    public function setOS($os)
    {
        return $this->setParam(self::SOFTWARE_OS_PARAM_NAME, $os);
    }

    /**
     * Get the operating system of the software download
     *
     * @return string
     */
    public function getOS()
    {
        return $this->getParam(self::SOFTWARE_OS_PARAM_NAME);
    }

    /**
     * Set the version of the software download.
     *
     * Version is specfied in the format `major.minor.point`.
     *
     * eg. `1.0.1`, `1.20.5`, `2.1.11`
     *
     * @param   string  $version    Software version
     *
     * @return self
     */
    public function setVersion($version)
    {
        return $this->setParam(self::SOFTWARE_VER_PARAM_NAME, $version);
    }

    /**
     * Get the version of the software download
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getParam(self::SOFTWARE_VER_PARAM_NAME);
    }
}
