<?php

/**
 * This file is part of the SmsSender package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace SmsSender\Result;

/**
 * @author Kévin Gomez <kevin_gomez@carpe-hora.com>
 */
interface ResultInterface
{
    /**
     * Tells if the sms was sent.
     *
     * @return bool
     */
    function isSent();

    /**
     * Returns the sms ID.
     *
     * @return string
     */
    function getId();

    /**
     * Returns the sms recipient.
     *
     * @return string
     */
    function getRecipient();

    /**
     * Returns the sms body.
     *
     * @return string
     */
    function getBody();

    /**
     * Returns the name of the person which sent the sms.
     *
     * @return string
     */
    function getOriginator();

    /**
     * Extracts data from an array.
     *
     * @param array $data   An array.
     */
    function fromArray(array $data = array());

    /**
     * Returns an array with data indexed by name.
     *
     * @return array
     */
    function toArray();
}

// vim: set softtabstop=4 tabstop=4 shiftwidth=4 autoindent: