<?php
declare(strict_types=1);

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
        $this->setParam(self::SOFTWARE_NAME, $software);
        return $this;
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
        $this->setParam(self::OS, $os);
        return $this;
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
        $this->setParam(self::VERSION, $version);
        return $this;
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
