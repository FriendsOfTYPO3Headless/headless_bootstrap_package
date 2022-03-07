*EXPERIMENTAL* This extension is currently still in development and not yet stable. It is likely to change, or even change drastically.

[![TYPO3 11](https://img.shields.io/badge/TYPO3-11-orange.svg)](https://get.typo3.org/version/11)
[![CI Status](https://github.com/FriendsOfTYPO3Headless/headless_bootstrap_package/workflows/CI/badge.svg)](https://github.com/FriendsOfTYPO3Headless/headless_bootstrap_package/actions)
[![Code Coverage Badge](https://github.com/FriendsOfTYPO3Headless/headless_bootstrap_package/blob/code-coverage-badge/badge.svg)](https://github.com/FriendsOfTYPO3Headless/headless_bootstrap_package/blob/code-coverage-badge/clover.xml)
[![Total Downloads](http://poser.pugx.org/friendsoftypo3headless/headless-bootstrap-package/downloads)](https://packagist.org/packages/friendsoftypo3headless/headless-bootstrap-package)
[![Latest Stable Version](http://poser.pugx.org/friendsoftypo3headless/headless-bootstrap-package/v)](https://packagist.org/packages/friendsoftypo3headless/headless-bootstrap-package)
[![License](http://poser.pugx.org/friendsoftypo3headless/headless-bootstrap-package/license)](https://packagist.org/packages/friendsoftypo3headless/headless-bootstrap-package)
[![PHP Version Require](http://poser.pugx.org/friendsoftypo3headless/headless-bootstrap-package/require/php)](https://packagist.org/packages/friendsoftypo3headless/headless-bootstrap-package)

# TYPO3 Extension "headless_bootstrap_package" - Provides TypoScript definitions for proper JSON output from EXT:bootstrap_package content elements
This extension provides integration to headless extension with "[EXT:bootstrap_package](https://github.com/benjaminkott/bootstrap_package)" extension.

It provides TypoScript rendering definitions to output the bootstrap_packages ContentElements as proper JSON.

This extension adds a "bootstrapPackage" key to every JSON page reponse containing the EXT:bootstrap_package constants/config.
It also merges the page.meta constants into the page.meta JSON array.

## Requirements
This Extension requires:
- [TYPO3](https://github.com/TYPO3) in version at least 11.5
- [EXT:headless](https://github.com/TYPO3-Headless/headless) in version at least 3.0.3
- [EXT:bk2k/bootstrap-package](https://github.com/benjaminkott/bootstrap_package) in version at least 12.0

## TYPO3 Installation
Install extension using composer\
``composer require friendsoftypo3headless/headless-bootstrap-package``

and then, include TypoScript template, and you are ready to go.

**Important**: Do **NOT** include the Setup/Constants provided by EXT:bootstrap_package since they would interfere with the EXT:headless page config.
Instead please include the provided "Headless Boostrap Package: Boostrap Package Constants" TypoScript config in order to gain access to the EXT:bootstrap_package constants.

## Credits
A special thanks goes to [TRIXIE Heimtierbedarf GmbH & Co. KG](https://www.trixie.de), which is sponsoring development of this extension.

## Developers involved in the project

- [Sven Petersen](https://github.com/svenpet90) ([DAUSKONZEPT GmbH](https:///www.dauskonzept.de) && [HardAnders GbR](https://www.hardanders.de))
- [Niels Seelhöfer](https://github.com/derseeli) ([TRIXIE Heimtierbedarf GmbH & Co. KG](https://www.trixie.de) && [Datenanker](https://www.datenanker.com))
