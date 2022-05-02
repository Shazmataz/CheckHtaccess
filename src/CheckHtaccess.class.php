<?php

/**
 * CheckHtaccess: A PHP based tool that keeps an eye on your .htaccess file!
 *
 * @see https://github.com/Shazmataz/CheckHtaccess/ The CheckHtaccess GitHub project
 *
 * @author Shaz Hossain (Shazmataz)
 * @copyright 2022 Shaz Hossain (Shazmataz)
 * @license Licensed under MIT (https://github.com/Shazmataz/CheckHtaccess/LICENSE)
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

 namespace CheckHtaccess;

 /**
  * Create a new instance of CheckHtaccess. 
  * CheckHtaccess: A PHP based tool that keeps an eye on your .htaccess file!
  * 
  * @author Shaz Hossain (Shazmataz)
 */
class CheckHtaccess {
  protected $bacukupHtFile;
  protected $currentHtFile;
  protected $createCurrentHFileIfNotFound;
  protected $echoProcess;
  protected $localLog;
  protected $localLogFile;
  protected $logErrorsToServerLogs;

 /**
  * Helper function to get the real current path
  * to use with checkHTWithCron()
  * Useful on shared hosting packages. 
  * 
  * @param string $htFile The name of the .htaccess backup or .htaccess file. Can be relative. 
  * @return string Full path of file, if exists, and whether the file is writable.  
  */
  public function getPathForCron(string $htFile = '.htaccess') {
    if(is_string($htFile)) {
      $rp = realpath($htFile);
      if(is_string($rp)) {
        if(is_writable($htFile)) {
          return $rp . PHP_EOL . 'File is writable';
        } else {
          return $rp . PHP_EOL . 'File is not writable';
        }
      } else if (!realpath($htFile)) {
        return $htFile.' not found';
      }
    } else {
      return 'Path expected as string';
    }
  }

 /**
  * Helper function to get any errors.
  * Useful for testing/debuging.
  * 
  * @return string Errors. If any.
  */
  public function getErrors() {
      return $this->logs;
  }

 /**
  * Performs the .htaccess check and restoration with all available options. 
  * If all logging options are false errors will still be logged to the system logs. 
  * 
  * @param string $currentHtFile The path and name of the backup .htaccess file. Can be relative. 
  * @param string $bacukupHtFile The path and name of the target .htaccess file. Can be relative. 
  * @param bool $createCurrentHFileIfNotFound Whether to create a new .htaccess file if it is not found. 
  * @param bool $echoProcess Whether to echo out the process. Only recommended for testing/debuging.
  * @param bool $localLog Whether to use a local file for logging errors and successful restorations. 
  * @param string $localLogFile The path and name of the local logging file. Can be relative.
  * @param bool $logErrorsToServerLogs Whether to log errors to the system logs.  
  * @return bool False on error.
  */
  public function checkHTFull(string $currentHtFile = '/path/to/.htaccess', string $bacukupHtFile = '/path/to/bak.htaccess', bool $createCurrentHFileIfNotFound = true, bool $echoProcess = false, bool $localLog = true, string $localLogFile = '/path/to/checkhtaccess.log', bool $logErrorsToServerLogs = false) {
    $err = 0;
    $this->logs = '';
    $this->successStat = '';

    $err += $this->validateParams(0,$currentHtFile,'checkHTFull');
    $err += $this->validateParams(1,$bacukupHtFile,'checkHTFull');
    $err += $this->validateParams(2,$createCurrentHFileIfNotFound,'checkHTFull');
    $err += $this->validateParams(3,$echoProcess,'checkHTFull');
    $err += $this->validateParams(4,$localLog,'checkHTFull');
    if($this->localLog) $err += $this->validateParams(5,$localLogFile,'checkHTFull');
    $err += $this->validateParams(6,$logErrorsToServerLogs,'checkHTFull');

    if($err == 0) {
      $this->chkOrigHTfile();
      return true;
    } else {
      return false;
      if(!$this->echoProcess && !$this->localLog && !$this->logErrorsToServerLogs) $this->logErrorsToServerLogs = true;
      $this->logData();
    }
  }

 /**
  * Use this function if you are using CheckHtaccess as a cron job to check and restore .htaccess files. 
  * Absolute paths are required. Use getPathForCron helper function if to find this. 
  * 
  * @param string $currentHtFile The ABSOLUTE path and name of the .htaccess file. Use getPathForCron helper function if 
  * you’re unable to find this. 
  * @param string $bacukupHtFile The ABSOLUTE path and name of the backup .htaccess file. Use getPathForCron helper function if 
  * you’re unable to find this. 
  * @param bool $createCurrentHFileIfNotFound Whether to create a new .htaccess file if it is not found. 
  * @param bool $localLog Whether to use a local file for logging errors and successful restorations. 
  * @param string $localLogFile The ABSOLUTE path and name of the local logging file. Use getPathForCron helper function if 
  * you’re unable to find this. 
  * @param bool $logErrorsToServerLogs Whether to log errors to the system logs.  
  * @return bool False on error.
  */
  public function checkHTCron(string $currentHtFile = '/full/path/to/.htaccess', string $bacukupHtFile = '/full/path/to/bak.htaccess', bool $createCurrentHFileIfNotFound = true, bool $localLog = true, string $localLogFile = '/full/path/to/checkhtaccess.log', bool $logErrorsToServerLogs = false) {
    $err = 0;
    $this->logs = '';
    $this->successStat = '';
    $this->echoProcess = false;

    $err += $this->validateParams(0,$currentHtFile,'checkHTCron');
    $err += $this->validateParams(1,$bacukupHtFile,'checkHTCron');
    $err += $this->validateParams(2,$createCurrentHFileIfNotFound,'checkHTCron');
    $err += $this->validateParams(4,$localLog,'checkHTCron');
    if($this->localLog) $err += $this->validateParams(5,$localLogFile,'checkHTCron');
    $err += $this->validateParams(6,$logErrorsToServerLogs,'checkHTCron');

    if($err == 0) {
      $this->chkOrigHTfile();
    } else {
      return false;
      $this->logData();
    }
  }

 /**
  * Performs the .htaccess check and restoration upon a 404 error. 
  * Does not output any echo. Must include this class & method within your 404 page. 
  * 
  * @param string $currentHtFile The path and name of the backup .htaccess file. Can be relative. 
  * @param string $bacukupHtFile The path and name of the target .htaccess file. Can be relative. 
  * @param bool $createCurrentHFileIfNotFound Whether to create a new .htaccess file if it is not found. 
  * @param bool $localLog Whether to use a local file for logging errors and successful restorations. 
  * @param string $localLogFile The path and name of the local logging file. Can be relative. 
  * @param bool $logErrorsToServerLogs Whether to log errors to the system logs. 
  * @return bool False on error.
  */
  public function checkHTon404(string $currentHtFile = '/path/to/.htaccess', string $bacukupHtFile = '/path/to/bak.htaccess', bool $createCurrentHFileIfNotFound = true, bool $localLog = true, string $localLogFile = '/full/path/to/checkhtaccess.log', bool $logErrorsToServerLogs = false) {
    $err = 0;
    $this->logs = '';
    $this->successStat = '';
    $this->echoProcess = false;

    $err += $this->validateParams(0,$currentHtFile,'checkHTon404');
    $err += $this->validateParams(1,$bacukupHtFile,'checkHTon404');
    $err += $this->validateParams(2,$createCurrentHFileIfNotFound,'checkHTon404');
    $err += $this->validateParams(4,$localLog,'checkHTon404');
    if($this->localLog) $err += $this->validateParams(5,$localLogFile,'checkHTon404');
    $err += $this->validateParams(6,$logErrorsToServerLogs,'checkHTon404');

    if($err == 0) {
      $this->chkOrigHTfile();
    } else {
      return false;
      $this->logData();
    }
  }

  /**
   * Validates provided options 
   */
  private function validateParams(int $param,$val,string $func) {
    switch ($param) {
      case 0:
        if($val != '/path/to/.htaccess' && $val != '/full/path/to/.htaccess' && $val != '') {
          $this->currentHtFile = $val;
        } else {
          $this->logs .='Invalid currentHtFile passed to ' . $func . '; ';
          return 1;
        };
        break;
      case 1:
        if($val != '/path/to/bak.htaccess' && $val != '/full/path/to/bak.htaccess' && $val != '') {
          $this->originalHtFile = $val;
        } else {
          $this->logs .='Invalid originalHtFile passed to ' . $func . '; ';
          return 1;
        };
        break;
      case 2:
        if(is_bool($val)) {
          $this->createCurrentHFileIfNotFound = $val;
        } else {
          $this->logs .='Invalid createCurrentHFileIfNotFound passed to ' . $func . '; ';
          return 1;
        };
        break;
      case 3:
        if(is_bool($val)) {
          $this->echoProcess = $val;
        } else {
          $this->logs .='Invalid echoProcess passed to ' . $func . '; ';
          return 1;
        };
        break;
      case 4:
        if(is_bool($val)) {
          $this->localLog = $val;
        } else {
          $this->logs .='Invalid localLog passed to ' . $func . '; ';
          return 1;
        };
        break;
        case 5:
          if($val != '/path/to/checkhtaccess.log' && $val != '/full/path/to/checkhtaccess.log' && $val != '' && strlen($val) > 1) {
            $this->localLogFile = $val;
          } else {
            $this->logs .='Invalid localLogFile passed to ' . $func . '; ';
            $this->localLog = false;
            return 1;
          };
          break;
          case 6:
            if(is_bool($val)) {
              $this->logErrorsToServerLogs = $val;
            } else {
              $this->logs .='Invalid logErrorsToServerLogs passed to ' . $func . '; ';
              return 1;
            };
            break;
    }
  }

 /**
  * Checks whether the given backup .htaccess file exists. 
  */
  private function chkOrigHTfile() {
    if(!is_file($this->originalHtFile))  {
      $this->logs .='Could not find original file: '.$this->originalHtFile.'; ';
      $this->logData();
    } else {
      $this->chkCurrHTfile();
    };
  }

 /**
  * Checks whether the given .htaccess file exists. 
  */
  private function chkCurrHTfile() {
    if(!is_file($this->currentHtFile))  {
      $this->logs .='Could not find current file: '.$this->currentHtFile.'; ';
      if($this->createCurrentHFileIfNotFound) {
        $this->createHTFile();
      } else {
        $this->logData();
      }
    } else {
      $this->chkHTfiles();
    };
  }

 /**
  * Compares the current .htaccess file with the backup 
  */
  private function chkHTfiles() {
    if(filesize($this->currentHtFile) !== filesize($this->originalHtFile))  {
        $cHTf = fopen($this->currentHtFile, 'rb');
        $oHTf = fopen($this->originalHtFile, 'rb');
      
        $result = true;
        while(!feof($cHTf)) {
          if(fread($cHTf, 8192) != fread($oHTf, 8192)) {
            $result = false;
            break;
          };
        };
        fclose($cHTf);
        fclose($oHTf);
      
        // Calls restoreHTfiles function if changes are found 
        if(!$result) {
          $this->restoreHTfiles($this->currentHtFile,$this->originalHtFile);
        } else {
          clearstatcache();
        }
    }
  }

 /**
  * Restores the backup .htaccess file to the current .htaccess file. 
  */
  private function restoreHTfiles($cHT,$oHT) {
    if(!copy($oHT, $cHT)) {
      $this->logs .='failed to copy '.$oHT.'; ';
    } else {
      $this->successStat .='Restored File; ';
    }
    $this->logData();
  }

 /**
  * Tries to create a .htaccess file when one is not found. 
  */
  private function createHTFile() {
    $htf = fopen($this->currentHtFile, 'a');
      if(!fwrite($htf, ' ')) {
        $this->logs .= $this->currentHtFile.'file write error!; ';
      }
    fclose($htf); 
    
    $this->successStat .='Created new current file: '.$this->currentHtFile.'; ';
    $this->restoreHTfiles($this->currentHtFile,$this->originalHtFile);
  }

 /**
  * Echoes / Logs any errors or output depending on setup. 
  * If cannot write to local file then will log errors to server logs 
  * regardless of whether the logErrorsToServerLogs is set to false. 
  */
  private function logData() {
    if($this->localLog) {
      $lf = fopen($this->localLogFile, 'a');
      if(!fwrite($lf, date('Y-m-d H:i:s').': '.$this->logs . $this->successStat.PHP_EOL)) {
        $this->logs.= 'Logfile write error!';
        $this->logErrorsToServerLogs = true;
      }
      fclose($lf);  
    }
    if($this->echoProcess) {
      echo date('Y-m-d H:i:s').': '.$this->logs . $this->successStat.PHP_EOL;
    }
    if($this->logErrorsToServerLogs) {
      error_log('CheckHtaccess.class: '.$this->logs . $this->successStat,0);
    }
    clearstatcache();
  }
};
?> 