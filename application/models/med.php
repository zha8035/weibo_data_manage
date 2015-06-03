<?php
	class Med extends CI_Model {

		function getNews() {
			$news = array();
			log_message("debug", "asdasd");
			$news[] = array(
					"title"=> "今日新闻",
					"imgurl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg"
				);
			$news[] = array(
				"title" => "四川省中医医院199期健康大讲堂",
				"description" =>" 糖尿病的胰岛素治疗
时间：2014年04月27日（周日）上午09:30
地点：成都中医药大学学术报告厅（成都市十二桥路37号）",
				"imgurl" => "http://www.sctcm120.com/zyxy/admin/uppic/5402142014213335_1.gif",
				"url" => "http://www.sctcm120.com/zyxy/pages/newsdetail.asp?id=1872"
			);
			$news[] = array(
				"title" => "关于申报2014年度四川省科技奖励的通知",
				"description" => "各科室、实验室：
    根据川科奖〔2014〕2号文件安排， 2014年度四川省科技奖励申报已开始，申报工作按照今年重新修订、颁布的《四川省科学技术奖励办法》、《四川省科学技术奖励办法实施细则》执行，现将有关事项通知如下： ",
				"url" => "http://www.sctcm120.com/zyxy/pages/newsdetail.asp?id=1870"
			);
			$news[] = array(
				"title" => "四川省中医医院198期健康大讲堂",
				"description" => "白内障的手术时机及晶体的选择
时间：2014年04月13日（周日）上午09:30
地点：成都中医药大学学术报告厅（成都市十二桥路37号）",
				"imgurl" => "http://www.sctcm120.com/zyxy/admin/uppic/562742014144934_1.gif",
				"url" => "http://www.sctcm120.com/zyxy/pages/newsdetail.asp?id=1848"
			);
			return $news;
		}
		
		function getStop() {
			$news = array();
                        $news[] = array(
                                        "title"=> "今日停诊",
                                        "description"=> "    1/5-3/5节假日均无停诊以挂号室当日为准
 
                  4/5今日停诊
 上午：刘贤文 常克
 下午：无
                  5/5今日停诊
 上午：汪淑钦 李唯一 吕斌 刘贤文
 下午：丁红
              
              长期停诊
  杨威英  林钰久   廖婷婷  曾洁萍  蒋建春
   曹 强   陈乃端  张光华   顾 婕   高悦
   陶陶    车 虹    胡云华  袁松柏  任培清
   殷丽萍  郭子光   赵冰洁  杨丽洁  丰 芬
   吴贤波  张薇薇   李鸿儒  苗润青  "
                                );
			return $news;
		}

	}
