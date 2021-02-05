# Xigen Hide Cache Warning

![phpcs](https://github.com/DominicWatts/HideCacheWarning/workflows/phpcs/badge.svg)

![PHPCompatibility](https://github.com/DominicWatts/HideCacheWarning/workflows/PHPCompatibility/badge.svg)

![PHPStan](https://github.com/DominicWatts/HideCacheWarning/workflows/PHPStan/badge.svg)

Hide the admin 'One or more of the Cache Types are invalidated' using a plugin

This warning can show for all kinds of reasons and generally can be ignored

# Install instructions #

`composer require dominicwatts/hidecachewarning`

`php bin/magento setup:upgrade`

# Usage instructions

Console command to invalidate cache. Or just wait for Magento do it for you.

    php bin/magento xigen:hidecachewarning:generate [-c|--cache CACHE]

    xigen:hidecachewarning:generate -c block_html

# Confirm plugin hides message

Before install

![Screenshot](https://i.snipboard.io/JiGrEa.jpg)

After install

![Screenshot](https://i.snipboard.io/6SmAeM.jpg)
