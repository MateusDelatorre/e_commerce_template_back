package com.example.demo.domain.usecase.product.dto;


import com.example.demo.domain.entities.product.Category;

import java.math.BigDecimal;

public interface IProductUpdateData {
    Long getId();
    String getDescription();
    String getName();
    BigDecimal getPrice();
    Integer getStockQuantity();
    Category getCategory();
    Boolean getActive();
}
