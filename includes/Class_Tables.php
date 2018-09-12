<?php
class Products
{
    //Data fields.
    var $prId;
    var $prCategory;
    var $prCode;
    var $prRegion;
    var $prRank;
    var $prTop;
    var $prActive;  
    var $prUpdateDate;
    var $prStartDate;
    var $prEndDate;
    var $prCouponPrice;
    var $prCouponType;
    var $prDescription;
    var $prEnName;
    var $prKrName;
    var $prImageName;
    
    /**
    * Retrieve the value for a given field.
    * This method is not normally called directly, instead use the following format: 
    * <code>$fieldValue = $education->fieldName;</code>
    *
    * @param    string        $fieldName        The name of the field to query.
    * @return    mixed        The value of the given field.
    */ 
    function __get( $fieldName ) 
    {
        if ( property_exists( $this, $fieldName )) 
        {
            return $this->$fieldName;
        } 
        else 
        {
            echo "Property '$fieldName' doesn't exist in class '".get_class( $this )."'.";
        }
    }

    /**
    * Set the value for a given field.
    * This method is not normally called directly, instead use the following format: 
    * <code>$education->fieldName = $fieldValue;</code>
    *
    * @param    string        $fieldName        The name of the field to set.
    * @param    mixed        $value            The new value for the field.
    */
    function __set( $fieldName, $value ) 
    {
        if ( property_exists( $this, $fieldName )) 
        {
            $this->$fieldName = $value;
        } 
        else 
        {
            echo "Property '$fieldName' doesn't exist in class '".get_class( $this )."'.";
        }
    }

    /**
    * Check if a given field has been set.
    * This method is not normally called directly, instead use the following format: 
    * <code>if ( isset( $education->fieldName )) { ... }</code>
    *
    * @param    string        $fieldName        The name of the field to check.
    */
    function __isset( $fieldName ) 
    {
        if ( property_exists( $this, $fieldName )) 
        {
            return isset( $this->$fieldName );
        } 
        else 
        {
            echo "Property '$fieldName' doesn't exist in class '".get_class( $this )."'.";
        }
    }

    /**
    * Unset a given field.
    * This method is not normally called directly, instead use the following format: 
    * <code>unset( $education->fieldName );</code>
    *
    * @param    string        $fieldName        The name of the field to unset.
    */
    function __unset( $fieldName ) 
    {
        if ( property_exists( $this, $fieldName )) 
        {
            unset( $this->$fieldName );
        } 
        else 
        {
            echo "Property '$fieldName' doesn't exist in class '".get_class( $this )."'.";
        }
    }
    /*
    function ReadImagelibrary_Sel($where, $page_section = false) 
    {        
        $query = "select * from imagelibrary $where $page_section";
        $result = Br_selectQuery($query);
                
        if ($result) 
        {
            return $result;
        }
        return false;
    }
    */
    
    function ReadProducts_Sel($where, $sort =  false, $page_section = false) 
    {        
        $query = Br_LIMITQUERY("products", $where, $sort, $page_section);
        $result = Br_selectQuery($query);
                
        if ($result)
        {
            return $result;
        }
        return false;
    }
    
    function ReadProductFatType1($prCategoryId) 
    {        
        $query = "select * from products where prCategory = '$prCategoryId' order by prRank desc, prUpdateDate desc";
        $result = Br_selectQuery($query);
        
        if ($result)
        {
            return $result;
        }
        return false;
    }
    
    
    //READ
    function ReadProducts($prId)
    {
        $que = "select * from products where prId = '$prId'";
        $sel = Br_selectQuery($que);
        $fat = Br_fatch_arryQuery($sel);
        
        $this->prId = $fat['prId'];
        $this->prCategory = $fat['prCategory'];
        $this->prCode = $fat['prCode'];
        $this->prRegion = $fat['prRegion'];
        $this->prRank = $fat['prRank'];
        $this->prTop = $fat['prTop'];
        $this->prActive = $fat['prActive'];
        $this->prUpdateDate = $fat['prUpdateDate'];
        
        $this->prStartDate = $fat['prStartDate'];
        $this->prEndDate = $fat['prEndDate'];
        $this->prCouponPrice = $fat['prCouponPrice'];
        $this->prCouponType = $fat['prCouponType'];
        $this->prDescription = $fat['prDescription'];
        
        $this->prEnName = $fat['prEnName'];
        $this->prKrName = $fat['prKrName'];
        $this->prImageName = $fat['prImageName'];
    }
    
    function SelectProducts($prodId)
    {
		$prodId = Br_dconv($prodId);
		$que="SELECT prodId FROM mfProd WHERE prodId ='".$prodId."'";

		$result = Br_selectQuery($que);
        $rows=Br_fatch_arryQuery($result);
		
		if ($rows['prodId'] ) 
        {
            return true;
        }
        return false;
	}

	//INSERT
    function InsertProducts($prodId, $prodType, $prodName, $prodKname,$suppName, $suppKname,$prodsize, $prodOUprice, $prodImportDate, $prodImage, $prodSeq,$useYN)
    {
		$prodId = Br_dconv($prodId);
        $prodType = Br_dconv($prodType);
		$prodName = Br_dconv($prodName);
        $prodKname = Br_dconv($prodKname);
		$suppName = Br_dconv($suppName);
		$suppKname = Br_dconv($suppKname);
        $prodsize = Br_dconv($prodsize);
        $prodOUprice = Br_dconv($prodOUprice);
        $prodImportDate = Br_dconv($prodImportDate);
        $prodImage = Br_dconv($prodImage);
		if($prodSeq =='')
			$prodSeq =999;

        $que = "insert into mfProd (prodId, prodType, prodName, prodKname, suppName, suppKname, prodsize, prodOUprice, prodImportDate, prodImage, prodSeq,useYN) values ";
        $que .= "('$prodId',";
        $que .= "'$prodType',";
        $que .= "'$prodName',";
        $que .= "'$prodKname',";
        $que .= "'$suppName',";
        $que .= "'$suppKname',";
        $que .= "'$prodsize',";
        $que .= "'$prodOUprice',";
        $que .= "'$prodImportDate',";
        $que .= "'$prodImage',";
		$que .= "$prodSeq,";
		$que .= "'$useYN')";

		$result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    //UPDATE
    function UpdateProducts($prodId, $prodType, $prodName, $prodKname,$suppName, $suppKname, $prodsize, $prodOUprice, $prodImportDate, $prodImage, $prodSeq,$useYN)
    {
        $prodId = Br_dconv($prodId);
        $prodType = Br_dconv($prodType);
		$prodName = Br_dconv($prodName);
        $prodKname = Br_dconv($prodKname);
		$suppName = Br_dconv($suppName);
		$suppKname = Br_dconv($suppKname);
        $prodsize = Br_dconv($prodsize);
        $prodOUprice = Br_dconv($prodOUprice);
        $prodImportDate = Br_dconv($prodImportDate);
        $prodImage = Br_dconv($prodImage);

		if($prodSeq == '')
			$prodSeq = 999;
		if($prodImage) {
			$str_qry = "prodImage = '$prodImage', ";
		} else {
			$str_qry = " ";
		}
		
        $que = "update mfProd set ";
        $que .= "prodType = '$prodType',";
        $que .= "prodName = '$prodName',";
        $que .= "prodKname = '$prodKname',";
        $que .= "suppName = '$suppName',";
        $que .= "suppKname = '$suppKname',";
        $que .= "prodsize = '$prodsize',";
        $que .= "prodOUprice = '$prodOUprice',";
        $que .= "prodImportDate = '$prodImportDate', ";
        $que .=  $str_qry;
        $que .= "prodSeq = $prodSeq, " ;
        $que .= "useYN = '$useYN' " ;
        $que .= "where prodId = '$prodId'";

		$result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    //DELETE
    function DeleteProducts($prodId)
    {
        //delete article
        $que = "delete from mfProd where prodId = '$prodId'";
        $result = Br_selectQuery($que);
        
        if($result) 
        {            
            return $result;
        }
        return false;
    }
    
    //delete all
    function DeleteProductsAll($prCategory)
    {
        //delete article
        $que = "delete from products where prCategory = '$prCategory'";
        $result = Br_selectQuery($que);
        
        if($result) 
        {            
            return $result;
        }
        return false;
    }
    
    //UPDATE
    function UpdateRank($prId, $prRank)
    {
        $que = "update products set ";
        $que .= "prRank = '$prRank'";     
        $que .= "where prId = '$prId'";
        
        $result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    function UpdateRankArray($prId, $prCode, $prRank, $prTop, $prActive, $prUpdateDate)
    {
        $prId = Br_dconv($prId);
        $prCode = Br_dconv($prCode);
        $prRank = Br_dconv($prRank);
        $prTop = Br_dconv($prTop);
        $prActive = Br_dconv($prActive);
        $prUpdateDate = Br_dconv($prUpdateDate);
        
        $que = "update products set ";
        $que .= "prCode = '$prCode',";
        $que .= "prRank = '$prRank',";
        $que .= "prTop = '$prTop',";
        $que .= "prActive = '$prActive',";
        $que .= "prUpdateDate = '$prUpdateDate'";
        $que .= "where prId = '$prId'";        
        
        $result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    
    function MakeProductsCategory($LANGUAGE, $type = false) 
    {
        $type_que="select * from products_category where prcActive = '1' order by prcId asc";
        $type_sel=Br_selectQuery($type_que);
        while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) 
        {
            if(empty($type))
            {
                echo "<option value='".$rows["prcId"]."'>".Br_iconv($rows["prcName_".$LANGUAGE])."</option>";
            }
            else
            {
                $selected= ($rows['prcId']==$type || Br_iconv($rows['prcName_'.$LANGUAGE]) == $type) ? 'selected' : "";
                echo "<option value='".$rows["prcId"]."' ".$selected.">".Br_iconv($rows["prcName_".$LANGUAGE])."</option>";
            }
        }
    }
        
    function ReadProductsCategoryName($pr_category) 
    {
        $que = "select prcName from products_category where prcId = '$pr_category'";
        $sel = Br_selectQuery($que);
        $fat = Br_fatch_arryQuery($sel);
        $result = Br_iconv($fat['prcName']);

        if($result) 
        {
            return $result;
        }
        return false;
    }
    
    function ReadProductsCategoryLogo($pr_category) 
    {
        $que = "select prcImage_korean, prcImage_english, prcImage_chinese, prcImage_japanese from products_category where prcId = '$pr_category'";
        $sel = Br_selectQuery($que);
        $fat = Br_fatch_arryQuery($sel);
        
        $result = Br_iconv($fat['prcImage_'.$_COOKIE['LANGUAGE']]);

        if($result) 
        {
            return $result;
        }
        return false;
    }
    
    function GetProductImageNameLocal($prod_id, $prod_svr)
    {
//        $que = "select top 1 piFolder, piImageName from plu_info where piPluId = '$prod_id' and piSvr = '$prod_svr'";
        $que = "select top 1 piFolder, piImageName from plu_info where piPluId = '$prod_id'";
//echo $que."<br>";
        $sel = Br_selectQuery($que);
        $fat = Br_fatch_arryQuery($sel);
        $image_folder = Br_iconv($fat['piFolder']);
        $image_name = Br_iconv($fat['piImageName']);

		if($image_folder && $image_name)
        {
            $image_file = PLUIMG.$image_folder."/".$image_name;
            $image_full = "<img src='".$image_file."' height='120px;'>";
			
			return $image_full;
            
            /*
            $image_file = iconv( "UTF-8", "EUC-KR", $image_file); 
            if(file_exists($image_file))
            {
                $image_full = "<img src='".$image_file."' height='120px;'>";
                return $image_full;
            }
            else
            {
                return false;
            }
            */
        }
        else
        {
            return false;
        }
    }
    
    function GetProductImageAddressLocal($prod_id, $prod_svr)
    {
//        $que = "select top 1 piFolder, piImageName from plu_info where piPluId = '$prod_id' and piSvr = '$prod_svr'";
        $que = "select top 1 piFolder, piImageName from plu_info where piPluId = '$prod_id'";
        $sel = Br_selectQuery($que);
        $fat = Br_fatch_arryQuery($sel);
        $image_folder = Br_iconv($fat['piFolder']);
        $image_name = Br_iconv($fat['piImageName']);
        
        if($image_folder && $image_name)
        {
            $image_file = PLUIMG.$image_folder."/".$image_name;
            return $image_file;
        }
        else
        {
            return false;
        }
    }

	function GetProductImageNameLocalFreesize($prod_id, $prod_svr, $size)
    {
        $que = "select top 1 piFolder, piImageName from plu_info where piPluId = '$prod_id' and piSvr = '$prod_svr'";
        $sel = Br_selectQuery($que);
        $fat = Br_fatch_arryQuery($sel);
        $image_folder = Br_iconv($fat['piFolder']);
        $image_name = Br_iconv($fat['piImageName']);
        
        if($image_folder && $image_name)
        {
            $image_file = PLUIMG.$image_folder."/".$image_name;
            $image_full = "<img src='".$image_file."' height='".$size."px;'>";
            return $image_full;
        }
        else
        {
            return false;
        }
    }
    
    function GetProductImageName($prod_id, $prod_type = false)
    {
        //dbhannam - 이미지 파일 네임 가져오기
        $que2 = "select top 1 photo_nm from dt_prod_sry where plu_cd = '$prod_id'";
        $sel2 = Br_selectQuery_dbhannam($que2);
        $gal_fat2 = Br_fatch_arryQuery($sel2);
        $image_filename = Br_iconv($gal_fat2['photo_nm']);
        
        //파일 네임이 있는 경우 폴더를 찾는다.
        if($image_filename && $prod_type)
        {
            //dbhannam - 이미지 경로 가져오기
            $que3 = "select top 1 FMC.buseo_cd as buseo_cd, FBB.nm as nm
            from ft_mfPtype1_com FMC 
            left outer join ft_buseo_bby FBB on FMC.buseo_cd = FBB.cd
            where FMC.pType = '$prod_type'";
            $sel3 = Br_selectQuery_dbhannam($que3);
            $gal_fat3 = Br_fatch_arryQuery($sel3);
            $image_folder = Br_iconv($gal_fat3['nm']);
            
            if($image_folder)
            {
                $image_file = "http://192.168.2.20/plu/COM/".$image_folder."/".$image_filename;
                //$image_file = "../img/visual/고객센터.jpg";
                //파일 사이즈 구하기
                /*
                $size = getimagesize($image_file);
                echo $width = $size[0];
                echo $height = $size[1];
                */
                
                $image_full = "<img src='".$image_file."' width='120px;' height='120px;'>";
                return $image_full;
            }
            else
            {
                return false;
            }
        }
        else
        {
            //없음 리턴
            return false;
        }
    }
    
    //prod 제품 카테고리 - plu_category 테이블
    function MakePRODCategory($language, $plc_boardId, $type = false) 
    {
        $type_que="select * from plu_category where plc_boardId = '$plc_boardId' order by plc_prodType asc";
        $type_sel=Br_selectQuery($type_que);
        while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) 
        {
            if(empty($type))
            {
                echo "<option value='".$rows["plc_prodType"]."'>".Br_iconv($rows["plc_prodSub_".$language])."</option>";
            }
            else
            {
                $selected= ($rows['plc_prodType']==$type || Br_iconv($rows['plc_prodSub_'.$language]) == $type) ? 'selected' : "";
                echo "<option value='".$rows["plc_prodType"]."' ".$selected.">".Br_iconv($rows["plc_prodSub_".$language])."</option>";
            }
        }
    }

    //전체 카테고리
    function MakePRODCategoryAll($language, $type = false) 
    {
        $type_que="select * from plu_category order by plc_prodType asc";
        $type_sel=Br_selectQuery($type_que);
        while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) 
        {
            if(empty($type))
            {
                echo "<option value='".$rows["plc_prodType"]."'>".Br_iconv($rows["plc_prodSub_".$language])."</option>";
            }
            else
            {
                $selected= ($rows['plc_prodType']==$type || Br_iconv($rows['plc_prodSub_'.$language]) == $type) ? 'selected' : "";
                echo "<option value='".$rows["plc_prodType"]."' ".$selected.">".Br_iconv($rows["plc_prodSub_".$language])."</option>";
            }
        }
    }
    
    function ShowPRODCategory($type, $language) 
    {
        $type_que="select top 1 plc_prodName_$language, plc_prodSub_$language from plu_category where plc_prodType = '$type'";
        $type_sel=Br_selectQuery($type_que);
        $fat=Br_fatch_arryQuery($type_sel);
    
        $result = Br_iconv($fat['plc_prodName_'.$language])." > ".Br_iconv($fat['plc_prodSub_'.$language]);
        
        if ($result)
        {
            return $result;
        }
        return false;
        
    }
        
    //WISH LIST ------------------------------------------------------------
    function ReadWishlist_Sel($where, $sort =  false, $page_section = false) 
    {        
         if($page_section)
        {
            $fno = explode(',', $page_section);
            $first = $fno[0];
            $second = $fno[1];
            
            $query = "select *, convert(varchar(20), mwUpdateDate, 120) as mwUpdateDate from  (select ROW_NUMBER() over($sort) as ROWNUM, * from member_wishlist $where) T where  T.ROWNUM BETWEEN ($first+1) AND ($first+$second)";
        } 
        else
        {
            $query = "select * from member_wishlist $where $sort";
        }
        $result = Br_selectQuery($query);
                
        if ($result)
        {
            return $result;
        }
        return false;
    }
    
    function CheckWishlist($mw_memberId, $mw_prodId, $mwStore)
    {
        //존재하는 상품인지 체크
        $query = "select top 1 mwId from member_wishlist where mw_memberId = '$mw_memberId' and mw_prodId = '$mw_prodId' and mwStore = '$mwStore'";
        $sel = Br_selectQuery($query);
        $row = Br_num_rowsQuery($sel);
        $result = $row;
        if ($result)
        {
            return $result;
        }
        return false;
    }
    
    function CheckWishlist_control($mw_id, $option)
    {
        //메일링 체크 박스 추가
        $que = "update member_wishlist set ";
        $que .= "mwMailOption = '$option'";     
        $que .= "where mwId = '$mw_id'";
        
        $result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    //INSERT
    function InsertWishlist($mwId, $mw_memberId, $mw_prodId, $mwStore, $mwUpdateDate, $mwMailOption)
    {
        $mwId = Br_dconv($mwId);
        $mw_memberId = Br_dconv($mw_memberId);
        $mw_prodId = Br_dconv($mw_prodId);
        $mwStore = Br_dconv($mwStore);
        $mwUpdateDate = Br_dconv($mwUpdateDate);
        $mwMailOption = Br_dconv($mwMailOption);
        
        //입력 하기
        $que = "insert into member_wishlist values ";
        //$que .= "prId = '$prId',";
        $que .= "('$mw_memberId',";
        $que .= "'$mw_prodId',";
        $que .= "'$mwStore',";
        $que .= "'$mwUpdateDate',";
        $que .= "'$mwMailOption')";
        
        $result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    //UPDATE - option mail
    function UpdateWishlistOption($mwId, $mwMailOption)
    {
        $que = "update member_wishlist set ";
        $que .= "mwMailOption = '$mwMailOption'";     
        $que .= "where mwId = '$mwId'";
        
        $result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    //DELETE
    function DeleteWishlist($mwId)
    {
        //delete article
        $que = "delete from member_wishlist where mwId = '$mwId'";
        $result = Br_selectQuery($que);
        
        if($result) 
        {            
            return $result;
        }
        return false;
    }
    
    function DeleteWishlistAll($member_id)
    {
        //delete article
        $que = "delete from member_wishlist where mw_memberId = '$member_id'";
        $result = Br_selectQuery($que);
        
        if($result) 
        {            
            return $result;
        }
        return false;
    }
    //PLU Desc ------------------------------------------------------------------------------------
    
    function ReadPluDesc_Sel($where, $sort =  false, $page_section = false) 
    {        
        $query = Br_LIMITQUERY("plu_desc", $where, $sort, $page_section);
        $result = Br_selectQuery($query);
                
        if ($result)
        {
            return $result;
        }
        return false;
    }
    
    //INSERT
    function InsertPluDesc($pd_prodId, $pdStore, $pdDescription, $pdIframe, $imageFile1, $pdUpdateDate)
    {
        $pd_prodId = Br_dconv($pd_prodId);
        $pdStore = Br_dconv($pdStore);
        $pdDescription = Br_dconv($pdDescription);
        $pdIframe = Br_dconv($pdIframe);
        $imageFile1 = Br_dconv($imageFile1);
        $pdUpdateDate = Br_dconv($pdUpdateDate);
        
        //입력 하기
        $que = "insert into plu_desc values ";
        //$que .= "prId = '$prId',";
        $que .= "('$pd_prodId',";
        $que .= "'$pdStore',";
        $que .= "'$pdDescription',";
        $que .= "'$pdIframe',";
        $que .= "'$imageFile1',";
        $que .= "'$pdUpdateDate')";
        
        $result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    //UPDATE - option mail
    function UpdatePluDesc($pdId, $pd_prodId, $pdStore, $pdDescription, $pdIframe, $imageFile1, $pdUpdateDate)
    {
        $pdId = Br_dconv($pdId);
        $pd_prodId = Br_dconv($pd_prodId);
        $pdStore = Br_dconv($pdStore);
        $pdDescription = Br_dconv($pdDescription);
        $pdIframe = Br_dconv($pdIframe);
        $imageFile1 = Br_dconv($imageFile1);
        $pdUpdateDate = Br_dconv($pdUpdateDate);
        
        $que = "update plu_desc set ";
        $que .= "pd_prodId = '$pd_prodId', ";     
        $que .= "pdStore = '$pdStore', ";     
        $que .= "pdDescription = '$pdDescription', ";     
        $que .= "pdIframe = '$pdIframe', ";     
        $que .= "imageFile1 = '$imageFile1', ";
        $que .= "pdUpdateDate = '$pdUpdateDate'";
        $que .= "where pdId = '$pdId'";
        
        $result = Br_selectQuery($que);
        
        if ( $result ) 
        {
            return $result;
        }
        return false;
    }
    
    //DELETE - images
    function DeleteImagePludesc($pdId, $image_no)
    {
        //delete article
        $que = "update plu_desc set imageFile".$image_no." = '' where pdId = '$pdId'";
        $result = Br_selectQuery($que);
        
        if($result) 
        {
            return $result;
        }
        return false;
    }
    
    //DELETE
    function DeletePluDesc($pdId)
    {
        //delete article
        $que = "delete from plu_desc where pdId = '$pdId'";
        $result = Br_selectQuery($que);
        
        if($result) 
        {            
            return $result;
        }
        return false;
    }

	//DELETE - images
	function DeleteImageProducts($prodId)
	{
		//delete article
		$que = "update mfProd set prodImage = '' where prodId = '$prodId'";
		$result = Br_selectQuery($que);
		
		if($result) 
		{
			return $result;
		}
		return false;
	}

}
?>