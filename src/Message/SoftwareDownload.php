<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A message representing a user downloading a software installer.
 */
class SoftwareDownload extends AbstractMessage
{
    const SOFTWARE_NAME = 'software';
    const VERSION = 'version';
    const OS = 'os';

    /**
     * Set the name of the software download
     *
     * @param string    $software   Software name
     * @return self
     */
    public function setSoftwareName($software)
    {
        return $this->setParam(self::SOFTWARE_NAME, $software);
    }

    /**
     * Get the name of the software download
     *
     * @return string
     */
    public function getSoftwareName()
    {
        return $this->getParam(self::SOFTWARE_NAME);
    }

    /**
     * Set the operating system of the software download
     *
     * @param string    $os    Operating system
     * @return self
     */
    public function setOS($os)
    {
        return $this->setParam(self::OS, $os);
    }

    /**
     * Get the operating system of the software download
     *
     * @return string
     */
    public function getOS()
    {
        return $this->getParam(self::OS);
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
        return $this->setParam(self::VERSION, $version);
    }

    /**
     * Get the version of the software download
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getParam(self::VERSION);
    }
}
