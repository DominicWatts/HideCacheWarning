<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\HideCacheWarning\Plugin\Backend\Magento\AdminNotification\Model\System\Message;

class CacheOutdated
{
    public function afterIsDisplayed(
        \Magento\AdminNotification\Model\System\Message\CacheOutdated $subject,
        $result
    ) {
        return $result;
        return false;
    }
}
