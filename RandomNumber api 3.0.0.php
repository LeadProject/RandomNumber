<?php
/**
 * @name RandomNumber
 * @main MinecraftLEAD\RandomNumber
 * @author ["MinecraftLEAD"]
 * @version 1.0.0
 * @api 3.9.4
 * @description Lead plugin
 */
 
namespace MinecraftLEAD;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class RandomNumber extends PluginBase implements Listener {
   
  public $pdb, $db;
  
  public static $instance;
  
  public static function getInstance()
  {
    return self::$instance;
  }
  
  public function onLoad()
  {
    self::$instance = $this;
  }
  
  public function onEnable()
  {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
	
	$a = new PluginCommand("rand", $this);
    $a->setPermission("");
    $a->setUsage("/rand <최소> <최대>");
    $a->setDescription("랜덤 번호 추출");
    $this->getServer()->getCommandMap()->register($this->getDescription()->getName(), $a);
    
    @mkdir($this->getDataFolder());
    $this->PlayerData = new Config($this->getDataFolder() . "PlayerData.yml", Config::YAML);
    $this->pdb = $this->PlayerData->getAll();
    
    @mkdir($this->getDataFolder());
    $this->DataBase = new Config($this->getDataFolder() . "DataBase.yml", Config::YAML);
    $this->db = $this->DataBase->getAll();
  }
  
  public function save()
  {
	$this->PlayerData->setAll($this->pdb); 
	$this->PlayerData->save();
	$this->DataBase->setAll($this->db); 
    $this->DataBase->save();
  }
  
  public function onCommand(CommandSender $sender, Command $command, string $label, array $array) : bool
  {
    $command = $command->getName();
    $player = $sender;
	if ($command == "rand") 
	{
		if (count($args) < 1) 
		{
			$player->sendMessage("§f/rand <최소> <최대>");
			return true;
		} else {
			if (!is_numeric($args[0]))
			{
				$player->sendMessage("§c최소 값이 숫자가 아닙니다.");
				$player->sendMessage("§f/rand §c<최소>§f <최대>");
				return true;
			}
			if (!is_numeric($args[1]))
			{
				$player->sendMessage("§c최대 값이 숫자가 아닙니다.");
				$player->sendMessage("§f/rand <최소> §c<최대>");
				return true;
			}
			if ((int) $args[0] > (int) $args[1]) 
			{
				$player->sendMessage("§c최소 값이 최대 보다 큽니다.");
				$player->sendMessage("§f/rand §c<최소>§f <최대>");
				return true;
			}
			if ((int) $args[0] === (int) $args[1]) 
			{
				$player->sendMessage("§c최소 값과 최대 값은 같아 질수 없습니다.");
				$player->sendMessage("§f/rand §<최소> <최대>");
				return true;
			}
			if ((int) $args[0] > PHP_INT_MAX) 
			{
				$player->sendMessage("§c최소 값이 너무 큽니다. (" . PHP_INT_MAX . " 이하 이어야 함)");
				$player->sendMessage("§f/rand §c<최소>§f <최대>");
				return true;
			}
			if ((int) $args[1] > PHP_INT_MAX) 
			{
				player->sendMessage("§c최대 값이 너무 큽니다. (" . PHP_INT_MAX . " 이하 이어야 함)");
				$player->sendMessage("§f/rand <최소>§c <최대>");
				return true;
			}
			if (0 > (int) $args[0]) 
			{
				$player->sendMessage("§c최소 값이 너무 작습니다. (0 이상 이어야 함)");
				$player->sendMessage("§f/rand §c<최소>§f <최대>");
				return true;
			}
			if (0 > (int) $args[1])
			{
				$player->sendMessage("§c최대 값이 너무 작습니다. (0 이상 이어야 함)");
				$player->sendMessage("§f/rand <최소>§c <최대>");
				return true;
			}
			$player->sendMessage("§a값: §f" . mt_rand((int) $args[0], (int) $args[1]));
			return true;
		}
	}
  }
}