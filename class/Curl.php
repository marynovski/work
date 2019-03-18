<?php

class Curl
{
    const PROGRESS_FILE = "progres.txt";
    const PROXY_FILE = "proxylist-13.csv";

    /**
     * @param string $url
     * @return bool|string
     */
    public function getHtml(string $url, $post = 0, $id = NULL)
    {
        $randomProxy = $this->getRandomProxy();
        $proxy = $randomProxy[0] . ':' . $randomProxy[1];
        $proxyauth = $randomProxy[2] . ':' . $randomProxy[3];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
//curl_setopt($ch, CURLPROXY_SOCKS5, 1);
//curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

//        if ($post == 1) {
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS,
//                "logType=MisLog&product=AdvListing&productID=".$id."&entryType=301&What=Κομμωτήρια+Κουρεία&Where=");
//
//
//        }

//        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: huj=$540997$; huje=__AdServer_UniqueID=6acb6ff3-6d87-4e1b-8835-38b8c7fcfde2; hujowo=4pnv2ygfzsn0zcjdjvialazw; ble=__AdServer_UniqueID=232653a8-67fd-49e8-9e2e-d260d16b99f8; bla=mveeay5fmn2emqkpc1p341af; blo=$540997$; xtvrn=$540997$; ASP.NET_SessionId=wxuqrxdmlvxqdpedfvbxw4yk"));
//curl_setopt($ch, CURLOPT_PROXY, $randomProxy[0]);
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
//curl_setopt($ch, CURLOPT_PROXYPORT, $randomProxy[1]);

        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }

    public function getRandomProxy()
    {
        $proxy_list = $this->getProxyList();
        return $this->randomProxy($proxy_list);
    }

    private function randomProxy($proxies)
    {
        $randomNumber = rand(0, count($proxies)-1);
        return $proxies[$randomNumber];
    }

    private function getProxyList()
    {
        $proxies = [];
        $fileName = self::PROXY_FILE;
        $proxyFile = fopen($fileName, 'r');
        while (($line = fgets($proxyFile)) !== false) {
            $proxies[] = explode(' ', $line);
        }
        fclose($proxyFile);
        $proxy_it = 0;
        foreach($proxies as $proxy) {
            $proxies[$proxy_it][3] = str_replace([' ', "\n", "\t"],'', $proxy[3]);
            $proxy_it++;
        }
        return $proxies;
    }

    /**
     * alias:getLastPageFromParsedResource
     */
    public function getLastLineInProgressFile()
    {
        $fp = fopen(self::PROGRESS_FILE, 'r');
        fseek($fp, -1, SEEK_END);
        $pos = ftell($fp);
        $LastLine = "";
// Loop backword util "\n" is found.
        while((($C = fgetc($fp)) != "\n") && ($pos > 0)) {
            $LastLine = $C.$LastLine;
            fseek($fp, $pos--);
        }
        fclose($fp);
        $last_line = explode(' ', $LastLine);
        var_dump($LastLine);

        if ($last_line[0] == "") {
            return 1;
        }
        return $last_line[0];
    }
}