package com.example.demo.domain.usecase.product.dto;

public interface IProductUpdateData {
    Long getId();
    String getDescription();
    String getName();
    Double getPrice();
    Integer getStockQuantity();
    String getCategory();
    String getImageUrl();
    Boolean getActive();
    java.util.List<String> getTags();
    java.util.List<String> getOptions();
}
