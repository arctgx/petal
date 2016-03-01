<?php

class fileTask extends task_base {

    public function downloadAction() {
        printf("task begin at %s\n", date('Y-m-d H:i:s'));

        $lastID = 0;
        $total = $success = $fail = 0;
        while (true) {
            $picInfoList = dao_file::getUndlPicList($lastID, 10);
            var_dump($picInfoList);
            if (empty($picInfoList)) {
                break;
            }

            foreach ($picInfoList as $onePic) {
                $lastID = $onePic['id'];
                $total++;

                $ret = fileDonwLoad::dlOnePic($onePic['file_key'], $onePic['file_type']);
                if (!$ret) {
                    printf("dl fail, id[%d] key[%s]\n", $onePic['id'], $onePic['file_key']);
                    $fail++;
                    continue;
                }
                dao_file::upPicDownloaded($onePic['id']);
                printf("dl success, id[%d] key[%s]\n", $onePic['id'], $onePic['file_key']);
                $success++;
            }
            // break; // for test
        }

        printf("task end at %s, total %d, success %d, fail %d\n", date('Y-m-d H:i:s'), $total, $success, $fail);
    }


}
