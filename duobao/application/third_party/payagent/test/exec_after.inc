<?php

fprintf(STDERR, "%s", "retcode=".$payFacade->_getRetCode()."\n");
fprintf(STDERR, "%s", "errmsg=".$payFacade->_getErrMsg()."\n");
fprintf(STDERR, "%s", "requrl=".$payFacade->_getRequestURL()."\n");
fprintf(STDERR, "%s", "reqdebug=".$payFacade->_getRequestDebugInfo()."\n");
fprintf(STDERR, "%s", "curlerrno=".$payFacade->_getCurlErrno()."\n");
fprintf(STDERR, "%s", "curlerrmsg=".$payFacade->_getCurlErrMsg()."\n");
fprintf(STDERR, "%s", "httpcode=".$payFacade->_getCurlHttpCode()."\n");
fprintf(STDERR, "%s", "res_content:<\n".$payFacade->_getResponseContent()."\n>\n");
fprintf(STDERR, "%s", "resdebug=".$payFacade->_getResponseDebugInfo()."\n");
fprintf(STDERR, "%s", "res_params:<\n".var_export($payFacade->_getResponseParameters(), true)."\n>\n");

