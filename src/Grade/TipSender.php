<?php

namespace Grade;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\Task;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\event\Listener;
use pocketmine\Event;
use pocketmine\utils\Utils;
use pocketmine\command\Command; 
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\level\Level; 
use pocketmine\command\CommandSender; 
use pocketmine\inventory\Inventory;
use pocketmine\utils\Config; 
use pocketmine\event\player\PlayerRespawnEvent; 
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\item\ItemBlock;  

use Grade\Grade;
use PocketVIP\PocketVIP;
use onebone\economyapi\EconomyAPI;
use FactionsPro\FactionMain;

class TipSender extends Task{
	private $plugin;

	public function __construct(Grade $plugin){
		$this->plugin = $plugin;
	}

	public function onRun($currentTick){  //当前时间
		if ($currentTick % 600 == 0){ //60*5*20 = 600 (5 min)
			$this->plugin->save();
			$this->plugin->getLogger()->info(TextFormat::GRAY."已自动保存");
		}

		

		foreach ($this->plugin->getServer()->getOnlinePlayers() as $key => $player) {
			
			if(isset($this->plugin->tip[$player->getName()]) and ($this->plugin->tip[$player->getName()] == false) ){
				continue;
			}

			$exp = $this->plugin->getEXP($player);
			$expM = $this->plugin->getMaxEXP($player);
			$grade = $this->plugin->getGrade($player);
			$title = $this->plugin->getTitle($grade);
			$mi = $this->plugin->getMI($player);
			
			$money = EconomyAPI::getInstance()->myMoney($player);
			$world = $player->getLevel()->getName();	
			$inven = $player->getInventory();
			$item = $inven->getItemInHand();
			$id = $item->getId();
			$xue = $player->getHealth();
			$gm = $player->getgamemode();
			$xueM = $player->getMaxHealth();
			$ts = $item->getDamage();
			$online = count($this->plugin->getServer()->getOnlinePlayers());
			$s = $this->plugin->getServer();
			$tps = (int)$s->getTicksPerSecondAverage();
			$cpu = (int)$s->getTickUsageAverage();
			$user = $player->getName();
			$this->factionspro=$this->plugin->getServer()->getPluginManager()->getPlugin("FactionsPro");
			if($this->factionspro->getPlayerFaction($user)==null){
            $ghs="未加入公会";
            }else{
            $ghs=$this->factionspro->getPlayerFaction($user);
            }
			
			$x = (int)$player->getX();
			$y = (int)$player->getY();
			$z = (int)$player->getZ();
				
			$yaw = (int)$player->getYaw();
				if (22.5 <= $yaw && $yaw < 67.5) {
				    $bearing = "西北方";
				} elseif (67.5 <= $yaw && $yaw < 112.5) {
					$bearing = "北方";
				} elseif (112.5 <= $yaw && $yaw < 157.5) {
					$bearing = "東北方";
				} elseif (157.5 <= $yaw && $yaw < 202.5) {
					$bearing = "東方";
				} elseif (202.5 <= $yaw && $yaw < 247.5) {
					$bearing = "東南方";
				} elseif (247.5 <= $yaw && $yaw < 292.5) {
					$bearing = "南方";
				} elseif (292.5 <= $yaw && $yaw < 337.5) {
					$bearing = "西南方";
				} else {
					$bearing = "西方";
				}
					

			if (PocketVIP::getInstance()->isSVIP($player->getName())){
				$qx = "SVIP";
			} elseif (PocketVIP::getInstance()->isVIP($player->getName())){
				$qx = "VIP";
			} else {
				if ($player->isOp()){
					$qx = "管理员";
				} else {
					$qx = "玩家";
				}
			}


			///////////////////////////


			
			///////////////////////////
			
			//$player->sendPopup($str);
			$player->sendTip("§eLv. $grade $title | §b经验值：$exp/$expM | §a钱包: $money 元 \n§e血量：$xue/$xueM | §d所在世界：$world | §c坐标：($x:$y:$z) $bearing \n§5节操：$mi | §b权限：$qx | §2在线人数：$online | §4公会：$ghs \n§f手持：$id: $ts | §b目前时间: ".date("H")." :".date("i")." :".date("s")." | §c流畅度：$tps | §d负荷量：$cpu%");
		}
		/*
		foreach ($this->plugin->needUpdate as $key => $player) {
			$this->plugin->addGrade($player,1);
			$this->plugin->setEXP($player, $this->plugin->getEXP($player) - $this->plugin->getMaxEXP($player), false);
			unset($this->plugin->needUpdate[$key]);
			break;
		}
		*/
	}
}
