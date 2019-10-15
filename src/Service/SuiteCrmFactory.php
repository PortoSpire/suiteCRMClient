<?php

/**
 * Description of SuiteCrmFactory
 * 
 * PHP version 7
 * 
 * * * License * * * 
 * PORTOSPIRE ("COMPANY") CONFIDENTIAL
 * Unpublished Copyright (c) 2016-2020 PORTOSPIRE, All Rights Reserved.
 * 
 * NOTICE: All information contained herein is, and remains the property of 
 * COMPANY. The intellectual and technical concepts contained herein are
 * proprietary to COMPANY and may be covered by U.S. and Foreign Patents, 
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material is 
 * strictly forbidden unless prior written permission is obtained from COMPANY.
 * Access to the source code contained herein is hereby forbidden to anyone 
 * except current COMPANY employees, managers or contractors who have executed 
 * Confidentiality and Non-disclosure agreements explicitly covering such access.
 * 
 * The copyright notice above does not evidence any actual or intended publication
 * or disclosure of this source code, which includes information that is 
 * confidential and/or proprietary, and is a trade secret, of COMPANY. 
 * ANY REPRODUCTION, MODIFICATION, DISTRIBUTION, PUBLIC  PERFORMANCE, OR
 * PUBLIC DISPLAY OF OR THROUGH USE OF THIS SOURCE CODE WITHOUT THE EXPRESS WRITTEN
 * CONSENT OF COMPANY IS STRICTLY PROHIBITED, AND IN VIOLATION OF APPLICABLE 
 * LAWS AND INTERNATIONAL TREATIES. THE RECEIPT OR POSSESSION OF THIS SOURCE CODE
 * AND/OR RELATED INFORMATION DOES NOT CONVEY OR IMPLY ANY RIGHTS TO REPRODUCE,
 * DISCLOSE OR DISTRIBUTE ITS CONTENTS, OR TO MANUFACTURE, USE, OR SELL ANYTHING 
 * THAT IT MAY DESCRIBE, IN WHOLE OR IN PART.
 * * * End License * * * 
 * 
 * @category  CategoryName
 * @package   PackageName
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2019 PORTOSPIRE
 * @license   https://portospire.com/policies Proprietary, Confidential
 * @version   GIT: $ID$
 * @link      https://portospire.com 
 */

namespace PortoSpire\SuiteCRMClient\Service;

use Psr\Container\ContainerInterface;
use PortoSpire\SuiteCRMClient\Service\SuiteCrm;

/**
 * Description of SuiteCrmFactory
 *
 * @category  CategoryName
 * @package   PackageName
 * @author    Andrew Wallace <andrew.wallace@portospire.com>
 * @copyright 2019 PORTOSPIRE
 * @license   https://portospire.com/policies Proprietary
 * @version   Release: @package_version@
 * @link      https://coderepo.portospire.com/#git_repo_name
 * @since     Class available since Release 0.0.0
 */
class SuiteCrmFactory
{
    public function __invoke(ContainerInterface $container) : SuiteCrm
    {
        $logger = null;
        if($container->has('Logger')){
            $logger = $container->get('Logger');
        } else {
            $logger = new Psr\Log\NullLogger();
        }
        return new SuiteCrm($logger);
    }
}
