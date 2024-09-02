#!/usr/local/bin/php
<?php

/*
 * Copyright (C) 2017-2021 Franco Fichtner <franco@opnsense.org>
 * Copyright (C) 2003-2004 Manuel Kasper <mk@neon1.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

 
// code has been outsourced so that it can be reused for a addon script

echo "\nWriting configuration...";
flush();
write_config(sprintf('%s configuration from console menu', $interface));
echo "done.\n";

system_resolver_configure(true);
interface_reset($interface);
interface_configure(true, $interface, true);
filter_configure_sync(true);

if ($restart_dhcpd) {
    plugins_configure('dhcp', true);
}

if ($restart_webgui) {
    plugins_configure('webgui', true);
}

echo "\n";

if ($intip != '' || $intip6 != '') {
    if (count($ifdescrs) == '1' or $interface == 'lan') {
        $intip = get_interface_ip($interface);
        $intip6 = get_interface_ipv6($interface);
        echo "You can now access the web GUI by opening\nthe following URL in your web browser:\n\n";
        $webuiport = !empty($config['system']['webgui']['port']) ? ":{$config['system']['webgui']['port']}" : '';
        if (is_ipaddr($intip)) {
            echo "    {$config['system']['webgui']['protocol']}://{$intip}{$webuiport}\n";
        }
        if (is_ipaddr($intip6)) {
            echo "    {$config['system']['webgui']['protocol']}://[{$intip6}]{$webuiport}\n";
        }
    }
}

/* rest now or hit CTRL-C */
sleep(3);
